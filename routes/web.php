<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Models\Transaction;
use App\Models\Client;

/*
|--------------------------------------------------------------------------
| Public Route
|--------------------------------------------------------------------------
*/

Route::get('/php-binary', function () {
    return [
        'php_binary' => PHP_BINARY,
        'pdo_mysql' => extension_loaded('pdo_mysql'),
        'mysqli' => extension_loaded('mysqli'),
    ];
});

Route::get('/phpinfo-test', function () {
    phpinfo();
});
Route::get('/debug-db', function () {
    return [
        'default' => config('database.default'),
        'database' => DB::connection()->getDatabaseName(),
    ];
});
Route::get('/', function () {
    return auth()->check() 
        ? redirect('/dashboard') 
        : redirect('/login');
});

Route::get('/test-login', function () {
    auth()->loginUsingId(1);
    return redirect('/dashboard');
});


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {

        $todayTransactions = Transaction::whereDate('created_at', today())->count();

        $todayWeight = Transaction::whereDate('created_at', today())
            ->sum('net_weight');

        $clients = Client::count();

        $recent = Transaction::latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'todayTransactions',
            'todayWeight',
            'clients',
            'recent'
        ));

    })->name('dashboard');
    
    //Weight
    Route::get('/weight', [TransactionController::class, 'weight']);
    
    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::get('/transactions/create', [TransactionController::class, 'create']);
    Route::post('/transactions', [TransactionController::class, 'store']);
    Route::get('/print/{id}', [TransactionController::class, 'print']);
    Route::post('/print-preview', [TransactionController::class, 'printPreview']);
    Route::get('/transactions/print', [TransactionController::class, 'printFiltered']);
    Route::get('/transactions/download-filtered', [TransactionController::class, 'downloadFiltered']);

    Route::get('/transactions/{transaction}/edit', [TransactionController::class, 'edit']);

    Route::put('/transactions/{transaction}', [TransactionController::class, 'update']);
  

    /*---------------\
    |    Reports     |
    \--------------*/
    Route::get('/clients', [ClientController::class, 'index']);
    Route::post('/clients', [ClientController::class, 'store']);
    Route::get('/clients/{id}/download', [ClientController::class, 'download']);
    Route::get('/clients/{id}/print', [ClientController::class, 'print']);


    // Clients
    Route::get('/clients/{id}/report',[ClientController::class, 'report']);
    Route::get('/clients/{client}', [ClientController::class, 'show']);
    Route::get('/client-report', [ClientController::class, 'reports']);
    Route::post('/client-report/save', [ClientController::class, 'saveReports']);
    Route::delete('/clients/{client}',[ClientController::class, 'destroy']);

    // Profile (Breeze default)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

    //Fetch address from previous transactions
    Route::get('/company-address', function (Request $request) {
        $company = $request->company;

        $last = \App\Models\Transaction::where('company', $company)
            ->latest()
            ->first();

        return response()->json([
            'address' => $last?->address
        ]);
    });


/*
|-----------------------------------|
| Breeze Auth Routes (IMPORTANT)    |
|-----------------------------------|
*/

require __DIR__.'/auth.php';