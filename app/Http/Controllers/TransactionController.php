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
    public function index()
    {
        $query = Transaction::query();

        if(request('company')) {
            $query->where('company', request('company'));
        }

        if(request('start_date') && request('end_date')) {
            $query->whereBetween('created_at', [
                request('start_date') . ' 00:00:00',
                request('end_date') . ' 23:59:59'
            ]);
        }

        if(request('month')) {
            $query->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);
        }

        $transactions = $query
            ->orderBy('transaction_no')
            ->get();

        $clients = Client::orderBy('company')->get();

        return view('index', compact(
            'transactions',
            'clients'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::orderBy('company')->get();

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
            //dd($request->all());
        
        $netWeight = $request->gross_weight - $request->tare_weight;

        $client = Client::firstOrCreate(
        ['company' => $request->company],
        ['address' => $request->address]
        );

        Transaction::create([
            'transaction_no' => $request->transaction_no,
            'plate_number' => $request->plate_number,
            'driver_name' => $request->driver_name,
            'representative_name' => $request->representative_name,
            'transaction_type' => $request->transaction_type,
            'material' => $request->material,
            'product' => $request->product,
            'company' => $request->company,
            'address' => $request->address,
            //'moisture_content' => $request->moisture_content,
            'gross_weight' => $request->gross_weight,
            'tare_weight' => $request->tare_weight,
            'net_weight' => $netWeight,
            'clerk' => auth()->user()->name,
            'approved_by' => $request->approved_by,
            'client_id' => $client->id,

            //timestamps when weight was taken
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
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
        $query = Transaction::query();

        if($request->company){
            $query->where('company', $request->company);
        }

        if($request->start_date && $request->end_date){
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $transactions = $query
            ->orderBy('transaction_no')
            ->get();

        return view('print-filtered', compact('transactions'));
    }

    public function downloadFiltered(Request $request)
    {
        $query = Transaction::query();

        if($request->company){
            $query->where('company', $request->company);
        }

        if($request->start_date && $request->end_date){
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $transactions = $query
            ->orderBy('transaction_no')
            ->get();

        $pdf = Pdf::loadView(
        'download-filtered',
        compact('transactions')
        );

        return $pdf->download('Filtered-Transactions.pdf');
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
