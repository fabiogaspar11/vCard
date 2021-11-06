<?php

use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*************************************** Vcards ***************************************/

Route::get('vcards', [VcardController::class, 'getVcards']);

Route::get('vcards/{vcard}', [VcardController::class, 'getVcard']);

Route::post('vcards', [VcardController::class, 'store']);

Route::put('vcards/{vcard}', [VcardController::class, 'update']);

Route::delete('vcards/{vcard}', [VcardController::class, 'destroy']);

Route::get('transactions', [TransactionController::class, 'get']);


Route::get('transactions/{transaction}', [TransactionController::class, 'getVcardTransactions']);


