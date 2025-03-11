<?php

// use App\Classes\Mikrotik;

// use App\Classes\MikrotikService as ClassesMikrotikService;
// use App\Services\MikrotikService;

// use App\Services\MikrotikService;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MikrotikController;
use App\Http\Controllers\PackageController;
// use App\Services\MikrotikService;
use Illuminate\Support\Facades\Route;
use MikrotikSdk\MikrotikSdk;
use RouterOS\Client;
use RouterOS\Query;

// use MikrotikSdk\RouterosAPI;
// use App\Services\MikrotikService;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/login', [AuthController::class, 'loginInfo'])->name('det');
Route::get('/test', function () {
    $client = new Client([
        "host" => '192.168.0.126',
        "user" => 'admin',
        "pass" =>  '123123123'
    ]);

    $query = new Query("/log/print");
    $logs = $client->query($query)->read();
    print_r($logs);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('users', UserController::class);
    Route::apiResource('packages', PackageController::class);

    Route::get('/mikrotik/status', [MikrotikController::class, 'status']);
    Route::get('/mikrotik/interfaces', [MikrotikController::class, 'interfaces']);
    Route::get('/mikrotik/get-users', [MikrotikController::class, 'getUsers']);
    Route::post('/mikrotik/add-user', [MikrotikController::class, 'addUser']);
    Route::delete('/mikrotik/remove-user', [MikrotikController::class, 'removeUser']);
    Route::post('/mikrotik/bandwidth', [MikrotikController::class, 'setBandwidth']);

});
