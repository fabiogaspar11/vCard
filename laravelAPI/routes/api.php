<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentTypesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\VcardController;
use App\Http\Controllers\TransactionController;

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

/*************************************** Users ***************************************/

Route::get('users',[UserController::class, 'getUsers']);

Route::get('users/{user}',[UserController::class, 'getUser']);

Route::post('users', [UserController::class, 'storeUser']);

Route::put('users/{user}', [UserController::class, 'updateUser']);

Route::delete('users/{user}', [UserController::class, 'destroyUser']);

/*************************************** PaymentTypes ***************************************/

Route::get('paytypes',[PaymentTypesController::class, 'getPaymentsTypes']);

Route::get('paytypes/{payment_type}',[PaymentTypesController::class, 'getPaymentType']);

/*************************************** Categories ***************************************/

Route::get('categories',[CategoryController::class, 'getCategories']);

Route::get('categories/{category}',[CategoryController::class, 'getCategory']);

/*************************************** Categories ***************************************/

Route::get('defaultCategories',[CategoryController::class, 'getDefaultCategories']);

Route::get('defaultCategories/{defaultCategory}',[CategoryController::class, 'getDefaultCategory']);

/*************************************** Vcards ***************************************/

Route::get('vcards', [VcardController::class, 'getVcards']);

Route::get('vcards/{vcard}', [VcardController::class, 'getVcard']);

Route::post('vcards', [VcardController::class, 'storeVcard']);

Route::put('vcards/{vcard}', [VcardController::class, 'updateVcard']);

Route::delete('vcards/{vcard}', [VcardController::class, 'destroyVcard']);

/*************************************** Transactions ***************************************/

Route::get('transactions', [TransactionController::class, 'getTransactions']);

Route::get('transactions/{transaction}', [TransactionController::class, 'getVcardTransactions']);


