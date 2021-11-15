<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentTypesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\VcardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Api\AuthController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user()->token();
});

//Route::middleware('auth:api1')->get('/vcard', [VcardController::class, 'getVcard']);

Route::middleware('auth:api1')->get('/vcard', function (Request $request) {
    return $request->user();
});

/*************************************** Users ***************************************/

Route::get('users',[UserController::class, 'getUsers']);

Route::get('users/{user}',[UserController::class, 'getUser']);

Route::post('users', [UserController::class, 'storeUser']);

Route::put('users/{user}', [UserController::class, 'updateUser']);

Route::delete('users/{user}', [UserController::class, 'destroyUser']);

/*************************************** PaymentTypes ***************************************/

Route::get('paytypes',[PaymentTypesController::class, 'getPaymentsTypes']);

Route::get('paytypes/{paytype}',[PaymentTypesController::class, 'getPaymentType']);

/*************************************** Categories ***************************************/

Route::get('categories',[CategoryController::class, 'getCategories']);

Route::get('categories/{category}',[CategoryController::class, 'getCategory']);

/*************************************** Vcards ***************************************/

Route::get('vcards', [VcardController::class, 'getVcards']);

Route::get('vcards/{vcard}', [VcardController::class, 'getVcard']);

Route::post('vcards', [VcardController::class, 'storeVcard']);

Route::put('vcards/{vcard}', [VcardController::class, 'updateVcard']);

Route::delete('vcards/{vcard}', [VcardController::class, 'destroyVcard']);

/*************************************** Transactions ***************************************/

Route::get('transactions', [TransactionController::class, 'getTransactions']);

Route::get('transactions/{transaction}', [TransactionController::class, 'getVcardTransactions']);


/*************************************** Login ***************************************/

Route::post('login', [AuthController::class, 'login']);

//Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout']);
//Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout']);


Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api', 'auth:api1');
