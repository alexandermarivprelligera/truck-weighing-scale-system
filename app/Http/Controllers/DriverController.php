<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Driver;

class DriverController extends Controller
{
    public function index(){

    }

    public function store(Request $request)
    {
        Driver::create([
            'client_id' => $request->client_id,
            'driver_name' => $request->driver_name,
            'plate_number' => $request->plate_number,
        ]);

        return back()->with('success', 'Driver added successfully.');
    }

    public function update(){
        
    }

    public function destroy(Driver $driver)
    {
        $driver->delete();

        return back()->with('success', 'Driver deleted.');
}
}