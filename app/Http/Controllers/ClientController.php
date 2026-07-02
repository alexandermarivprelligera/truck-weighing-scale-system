<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Barryvdh\DomPDF\Facade\Pdf;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::query();

        if($request->search) {
            $query->where('company', 'like',
                '%' . $request->search . '%');
        }

        $clients = $query
            ->orderBy('company')
            ->get();

        return view('clients', compact('clients'));
    }

    public function store(Request $request)
    {
        Client::create([
            'company' => $request->company,
            'address' => $request->address
        ]);

        return redirect('/clients')
            ->with('success', 'Client Registered');
    }

    public function show($id)
    {
        $client = Client::with('transactions')->findOrFail($id);

        $transactions = $client->transactions;

        return view('client-details',compact('client', 'transactions'));
    }

    public function report($id)
    {
        $client = Client::with('transactions')->findOrFail($id);

        return view('client-report',compact('client'));
    }

    public function print($id)
    {
        $client = Client::with('transactions')->findOrFail($id);

        return view('client-report-pdf', compact('client'));
    }

    public function download($id)
    {
        $client = Client::with('transactions')->findOrFail($id);

        $pdf = Pdf::loadView('client-report-download', compact('client'));

        return $pdf->download($client->company . '-report.pdf');
    }

    public function reports()
    {
        $clients = Client::orderBy('company')->get();

        return view('client-report',compact('clients'));
    }

    public function saveReports(Request $request)
    {
        foreach($request->clients as $id => $data)
        {
            Client::where('id', $id)->update([
                'branch_code' => $data['branch_code'] ?: 'N/A',
                'company' => $data['company'] ?: 'N/A',
                'address' => $data['address'] ?: 'N/A',
                'mayor' => $data['mayor'] ?: 'N/A',
                'tin_number' => $data['tin_number'] ?: 'N/A',
                'contact_person' => $data['contact_person'] ?: 'N/A',
                'contact_number' => $data['contact_number'] ?: 'N/A'
            ]);
        }

        return back()->with(
            'success',
            'Client list updated.'
        );
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->back()->with('success','Client deleted successfully.');
    }
}