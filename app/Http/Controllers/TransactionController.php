<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Transaction;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $transactions = $this->filteredTransactionsQuery($request)
            ->orderBy('transaction_no')
            ->get();

        $totalTransactions = $transactions->count();
        $totalNetWeight = $transactions->sum('net_weight');

        $clients = Client::with('drivers')->orderBy('company')->get();

        return view('index', compact(
            'transactions',
            'clients',
            'totalTransactions',
            'totalNetWeight'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::with('drivers')->orderBy('company')->get();

        $today = Carbon::now()->format('md');

        /*Lock rows to avoid duplicates*/
        $last = Transaction::where('transaction_no', 'like', $today . '-%')
            ->orderByDesc('id')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->transaction_no, 5);
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }

        $transactionNo = $today . '-' . $nextNumber;

        return view('create', compact('clients', 'transactionNo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return DB::transaction(function() use($request) {

        $today = Carbon::now()->format('md');

        $last = Transaction::where('transaction_no', 'like', $today . '-%')
            ->lockForUpdate()
            ->orderByDesc('id')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->transaction_no, 5);
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }

        $transactionNo = $today . '-' . $nextNumber;

        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'driver_id' => ['nullable', 'exists:drivers,id'],
        ]);

        $client = Client::findOrFail($validated['client_id']);

        if (!empty($validated['driver_id']) && !$client->drivers()->whereKey($validated['driver_id'])->exists()) {
            abort(422, 'The selected driver does not belong to this client.');
        }

        $netWeight = $request->gross_weight - $request->tare_weight;

        Transaction::create([
            'transaction_no' => $transactionNo,
            'plate_number' => $request->plate_number,
            'driver_name' => $request->driver_name,
            'representative_name' => $request->representative_name,
            'transaction_type' => $request->transaction_type,
            'material' => $request->material,
            'product' => $request->product,
            'company' => $client->company,
            'address' => $client->address,
            'gross_weight' => $request->gross_weight,
            'tare_weight' => $request->tare_weight,
            'net_weight' => $netWeight,
            'clerk' => auth()->user()->name,
            'approved_by' => $request->approved_by,
            'client_id' => $client->id,

            'gross_time' => $request->gross_time
            ? Carbon::parse($request->gross_time)->format('Y-m-d H:i:s')
            : null,

            'tare_time' => $request->tare_time
            ? Carbon::parse($request->tare_time)->format('Y-m-d H:i:s')
            : null,

            'net_time' => $request->net_time
            ? Carbon::parse($request->net_time)->format('Y-m-d H:i:s')
            : null,
        ]);

        return redirect('/transactions/create')->with('success', 'Transaction Saved!');
        });
    }
    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        $clients = Client::with('drivers')->orderBy('company')->get();

        return view('edit', compact('transaction', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'driver_id' => ['nullable', 'exists:drivers,id'],
        ]);
        $client = Client::findOrFail($validated['client_id']);

        if (!empty($validated['driver_id']) && !$client->drivers()->whereKey($validated['driver_id'])->exists()) {
            abort(422, 'The selected driver does not belong to this client.');
        }

        $transaction->update([
            'plate_number' => $request->plate_number,
            'driver_name' => $request->driver_name,
            'company' => $client->company,
            'address' => $client->address,
            'client_id' => $client->id,
            'transaction_type' => $request->transaction_type,
            'material' => $request->material,
            'product' => $request->product,
            'gross_weight' => $request->gross_weight,
            'tare_weight' => $request->tare_weight,
            'net_weight' => $request->gross_weight - $request->tare_weight,
            'gross_time' => $request->gross_time,
            'tare_time' => $request->tare_time,
            'net_time' => $request->net_time,
        ]);

        return redirect('/transactions')
            ->with('success','Transaction updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }

    public function print($id)
    {
        $transaction = \App\Models\Transaction::findOrFail($id);
        return view('print', compact('transaction'));
    }

    public function printFiltered(Request $request)
    {
        $transactions = $this->filteredTransactionsQuery($request)
            ->orderBy('transaction_no')
            ->get();

        $totalTransactions = $transactions->count();
        $totalNetWeight = $transactions->sum('net_weight');

        return view('print-filtered', compact('transactions', 'totalTransactions', 'totalNetWeight'));
    }

    public function downloadFiltered(Request $request)
    {
        $transactions = $this->filteredTransactionsQuery($request)
            ->orderBy('transaction_no')
            ->get();

        $totalTransactions = $transactions->count();
        $totalNetWeight = $transactions->sum('net_weight');

        $pdf = Pdf::loadView(
        'download-filtered',
        compact('transactions', 'totalTransactions', 'totalNetWeight')
        );

        return $pdf->download('Filtered-Transactions.pdf');
    }

    private function filteredTransactionsQuery(Request $request)
    {
        $query = Transaction::query();

        if ($request->company) {
            $query->where('company', $request->company);
        }

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59',
            ]);
        }

        if ($request->month) {
            $query->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);
        }

        return $query;
    }
    
    public function weight()
    {
        $weight = file_get_contents('http://127.0.0.1:5000/weight');

        return response()->json(json_decode($weight, true));
    }   

    public function printPreview(Request $request)
    {   //dd($request->all());
        $transaction = (object) [
            'transaction_no' => $request->transaction_no,
            'plate_number' => $request->plate_number,
            'driver_name' => $request->driver_name,
            'representative_name' => $request->representative_name,
            'material' => $request->material,
            'company' => $request->company,
            'address' => $request->address,
            'gross_weight' => $request->gross_weight,
            'tare_weight' => $request->tare_weight,
            'net_weight' => $request->gross_weight - $request->tare_weight,
            'gross_time' => $request->gross_time,
            'tare_time' => $request->tare_time,
            'net_time' => $request->net_time,
            'clerk' => auth()->user()->name,
            'approved_by' => $request->approved_by,
        ];

        return view('print', compact('transaction'));
    }

}
