<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Transaction;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $transactions = $this->filteredTransactionsQuery($request)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
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

        $transactionNo = $this->nextTransactionNumber();

        return view('create', compact('clients', 'transactionNo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return DB::transaction(function() use($request) {

        $submittedNo = trim((string) $request->transaction_no);
        $request->merge(['transaction_no' => $submittedNo]);

        $transactionNo = $submittedNo !== ''
            ? $submittedNo
            : $this->nextTransactionNumber(true);

        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'driver_id' => ['nullable', 'exists:drivers,id'],
            'transaction_no' => ['nullable', 'string', 'max:255', Rule::unique('transactions', 'transaction_no')],
            'transaction_date' => ['nullable', 'date'],
            'gross_time' => ['nullable', 'date'],
            'tare_time' => ['nullable', 'date'],
            'net_time' => ['nullable', 'date'],
        ], [
            'transaction_no.unique' => 'This transaction number is already taken.',
        ]);

        $client = Client::findOrFail($validated['client_id']);

        if (!empty($validated['driver_id']) && !$client->drivers()->whereKey($validated['driver_id'])->exists()) {
            abort(422, 'The selected driver does not belong to this client.');
        }

        $netWeight = ($request->gross_weight + $request->add_on) - $request->tare_weight;

        $transaction = Transaction::create([
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
            'add_on' => $request->add_on,
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

        // Optional: let the clerk set/backdate the transaction date & time.
        if ($request->filled('transaction_date')) {
            $transaction->created_at = Carbon::parse($request->transaction_date);
            $transaction->save();
        }

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
        $submittedNo = trim((string) $request->transaction_no);
        $request->merge(['transaction_no' => $submittedNo]);

        $numberRules = ['nullable', 'string', 'max:255'];
        // Only check uniqueness when the clerk actually changes the number, so
        // old rows that already share a number can still be edited and saved.
        if ($submittedNo !== '' && $submittedNo !== $transaction->transaction_no) {
            $numberRules[] = Rule::unique('transactions', 'transaction_no')->ignore($transaction->id);
        }

        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'driver_id' => ['nullable', 'exists:drivers,id'],
            'transaction_no' => $numberRules,
            'transaction_date' => ['nullable', 'date'],
            'gross_time' => ['nullable', 'date'],
            'tare_time' => ['nullable', 'date'],
            'net_time' => ['nullable', 'date'],
        ], [
            'transaction_no.unique' => 'This transaction number is already taken.',
        ]);
        $client = Client::findOrFail($validated['client_id']);

        if (!empty($validated['driver_id']) && !$client->drivers()->whereKey($validated['driver_id'])->exists()) {
            abort(422, 'The selected driver does not belong to this client.');
        }

        // Optional: let the clerk change/backdate the transaction date & time.
        if ($request->filled('transaction_date')) {
            $transaction->created_at = Carbon::parse($request->transaction_date);
        }

        $transaction->update([
            'transaction_no' => $submittedNo !== ''
                ? $submittedNo
                : $transaction->transaction_no,
            'plate_number' => $request->plate_number,
            'driver_name' => $request->driver_name,
            'company' => $client->company,
            'address' => $client->address,
            'client_id' => $client->id,
            'transaction_type' => $request->transaction_type,
            'material' => $request->material,
            'product' => $request->product,
            'gross_weight' => $request->gross_weight,
            'add_on' => $request->add_on,
            'tare_weight' => $request->tare_weight,
            'net_weight' => ($request->gross_weight + $request->add_on) - $request->tare_weight,
            'gross_time' => $request->gross_time ? Carbon::parse($request->gross_time)->format('Y-m-d H:i:s') : null,
            'tare_time' => $request->tare_time ? Carbon::parse($request->tare_time)->format('Y-m-d H:i:s') : null,
            'net_time' => $request->net_time ? Carbon::parse($request->net_time)->format('Y-m-d H:i:s') : null,
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

    /**
     * Build the next transaction number for today (MMDD-0001).
     *
     * Uses the highest numeric suffix already issued today (not the first row
     * of the day), so the pre-filled number is correct after the first entry.
     * Manually typed numbers with a non-numeric suffix are ignored.
     */
    private function nextTransactionNumber(bool $lockForUpdate = false): string
    {
        $prefix = Carbon::now()->format('md') . '-';

        $query = Transaction::where('transaction_no', 'like', $prefix . '%');
        if ($lockForUpdate) {
            $query->lockForUpdate();
        }

        $max = 0;
        foreach ($query->pluck('transaction_no') as $number) {
            $suffix = substr($number, strlen($prefix));
            if ($suffix !== '' && ctype_digit($suffix)) {
                $max = max($max, (int) $suffix);
            }
        }

        return $prefix . str_pad((string) ($max + 1), 4, '0', STR_PAD_LEFT);
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
            'add_on' => $request->add_on,
            'tare_weight' => $request->tare_weight,
            'net_weight' => ($request->gross_weight + $request->add_on) - $request->tare_weight,
            'gross_time' => $request->gross_time ? Carbon::parse($request->gross_time)->format('Y-m-d H:i:s') : null,
            'tare_time' => $request->tare_time ? Carbon::parse($request->tare_time)->format('Y-m-d H:i:s') : null,
            'net_time' => $request->net_time ? Carbon::parse($request->net_time)->format('Y-m-d H:i:s') : null,
            'created_at' => $request->transaction_date ? Carbon::parse($request->transaction_date) : Carbon::now(),
            'clerk' => auth()->user()->name,
            'approved_by' => $request->approved_by,
        ];

        return view('print', compact('transaction'));
    }

}
