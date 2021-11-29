<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentTypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\VcardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\DefaultCategoryController;

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
    return $request->user();
});

Route::middleware(['auth:api'])->group(function (){

    /*************************************** Users ***************************************/

    Route::get('users',[UserController::class, 'getUsers']);

    Route::get('users/{user}',[UserController::class, 'getUser']);

    Route::post('users', [UserController::class, 'storeUser']);

    Route::put('users/{user}', [UserController::class, 'updateUser']);

    Route::delete('users/{user}', [UserController::class, 'destroyUser']);

    /*************************************** PaymentTypes ***************************************/

    Route::get('paytypes',[PaymentTypeController::class, 'getPaymentsTypes']);

    Route::get('paytypes/{payment_type}',[PaymentTypeController::class, 'getPaymentType']);

    /*************************************** Categories ***************************************/

    Route::get('categories',[CategoryController::class, 'getCategories']);

    Route::get('categories/{category}',[CategoryController::class, 'getCategory']);

    Route::post('categories', [CategoryController::class, 'storeCategory']);

    Route::put('categories/{category}', [CategoryController::class, 'updateCategory']);

    Route::delete('categories/{category}', [CategoryController::class, 'destroyCategory']);

    /*************************************** Categories ***************************************/

    Route::get('defaultCategories',[DefaultCategoryController::class, 'getDefaultCategories']);

    Route::get('defaultCategories/{defaultCategory}',[DefaultCategoryController::class, 'getDefaultCategory']);

    Route::post('defaultCategories', [DefaultCategoryController::class, 'storeDefaultCategory']);

    Route::put('defaultCategories/{defaultCategory}', [DefaultCategoryController::class, 'updateDefaultCategory']);

    Route::delete('defaultCategories/{defaultCategory}', [DefaultCategoryController::class, 'destroyDefaultCategory']);

    /*************************************** Vcards ***************************************/

    Route::get('vcards', [VcardController::class, 'getVcards']);

    Route::get('vcards/{vcard}', [VcardController::class, 'getVcard']);

    Route::put('vcards/{vcard}', [VcardController::class, 'updateVcard']);

    Route::delete('vcards/{vcard}', [VcardController::class, 'destroyVcard']);

    Route::get('vcards/{vcard}/transactions/lastTransaction', [VcardController::class, 'getVcardLastTransaction']);

    Route::get('vcards/{vcard}/transactions', [VcardController::class, 'getVcardTransactions']);

    Route::get('vcards/storage/{vcard}', [VcardController::class, 'getVcardPhoto']);

    /*************************************** Transactions ***************************************/

    Route::post('transactions', [TransactionController::class, 'storeTransaction']);

    Route::get('transactions', [TransactionController::class, 'getTransactions']);

    Route::get('transactions/{transaction}', [TransactionController::class, 'getVcardTransactions']);

    Route::put('transactions/{transaction}', [TransactionController::class, 'updateTransaction']);

    /*************************************** Logout ***************************************/

    Route::post('/logout', [AuthController::class, 'logout']);
});

/*************************************** Login ***************************************/

Route::post('/login', [AuthController::class, 'login']);

/*************************************** Create vcard ***************************************/

Route::post('vcards', [VcardController::class, 'storeVcard']);

