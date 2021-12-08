<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VcardController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PaymentTypeController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\AdministratorController;
use App\Http\Controllers\Api\DefaultCategoryController;
use App\Http\Controllers\Api\StatisticController;

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

Route::middleware(['auth:api','not.blocked'])->group(function (){

    /*************************************** Users ***************************************/

    Route::get('administrators',[AdministratorController::class, 'getAdministrators'])->middleware('can:viewAny,App\Models\Administrator');

    Route::get('administrators/{admin}',[AdministratorController::class, 'getAdministrator'])->middleware('can:view,admin');

    Route::post('administrators', [AdministratorController::class, 'storeAdministrator'])->middleware('can:create,App\Models\Administrator');

    Route::put('administrators/{admin}', [AdministratorController::class, 'updateAdministrator'])->middleware('can:update,admin');

    Route::delete('administrators/{admin}', [AdministratorController::class, 'destroyAdministrator'])->middleware('can:delete,admin');

    /*************************************** PaymentTypes ***************************************/

    Route::get('paymentTypes',[PaymentTypeController::class, 'getPaymentsTypes']);

    /*************************************** Categories ***************************************/

    Route::get('categories',[CategoryController::class, 'getCategories'])->middleware('can:viewAny,App\Models\Category');

    Route::get('categories/{category}',[CategoryController::class, 'getCategory'])->middleware('can:view,category');

    Route::post('categories', [CategoryController::class, 'storeCategory'])->middleware('can:create,App\Models\Category');

    Route::put('categories/{category}', [CategoryController::class, 'updateCategory'])->middleware('can:update,category');

    Route::delete('categories/{category}', [CategoryController::class, 'destroyCategory'])->middleware('can:delete,category');


    /*************************************** Default Categories ***************************************/

    Route::get('defaultCategories',[DefaultCategoryController::class, 'getDefaultCategories'])->middleware('can:viewAny,App\Models\DefaultCategory');

    Route::get('defaultCategories/{defaultCategory}',[DefaultCategoryController::class, 'getDefaultCategory'])->middleware('can:view,defaultCategory');

    Route::post('defaultCategories', [DefaultCategoryController::class, 'storeDefaultCategory'])->middleware('can:create,App\Models\DefaultCategory');

    Route::put('defaultCategories/{defaultCategory}', [DefaultCategoryController::class, 'updateDefaultCategory'])->middleware('can:update,defaultCategory');

    Route::delete('defaultCategories/{defaultCategory}', [DefaultCategoryController::class, 'destroyDefaultCategory'])->middleware('can:delete,defaultCategory');

    /*************************************** Vcards ***************************************/

    Route::get('vcards', [VcardController::class, 'getVcards'])->middleware('can:viewAny,App\Models\Vcard');

    Route::get('vcards/{vcard}', [VcardController::class, 'getVcard'])->middleware('can:view,vcard');

    Route::get('vcards/{vcard}/alterBlock', [VcardController::class, 'alterBlock'])->middleware('can:update,vcard');

    Route::put('vcards/{vcard}/alterDebitLimit', [VcardController::class, 'alterDebitLimit'])->middleware('can:update,vcard');

    Route::get('vcards/{vcard}/isVcard', [VcardController::class, 'checkVcard']);

    Route::put('vcards/{vcard}/password/change', [VcardController::class, 'changePassword'])->middleware('can:update,vcard');

    Route::put('vcards/{vcard}/confirmationCode/change', [VcardController::class, 'changeConfirmationCode'])->middleware('can:update,vcard');

    Route::put('vcards/{vcard}', [VcardController::class, 'updateVcard'])->middleware('can:update,vcard');

    Route::delete('vcards/{vcard}', [VcardController::class, 'destroyVcard'])->middleware('can:delete,vcard');

    Route::get('vcards/{vcard}/transactions/lastTransaction', [VcardController::class, 'getVcardLastTransaction'])->middleware('can:view,vcard');

    Route::get('vcards/{vcard}/transactions', [VcardController::class, 'getVcardTransactions'])->middleware('can:view,vcard');

    Route::get('vcards/storage/{vcard}', [VcardController::class, 'getVcardPhoto'])->middleware('can:view,vcard');

    Route::get('vcards/{vcard}/categories',[VcardController::class, 'getVcardCategories'])->middleware('can:view,vcard');

    Route::get('vcards/{vcard}/piggyBankState',[VcardController::class, 'piggyBankState'])->middleware('can:view,vcard');

    Route::get('vcards/{vcard}/createPiggyBank',[VcardController::class, 'createPiggyBank'])->middleware('can:update,vcard');

    Route::get('vcards/{vcard}/getPiggyBankBalance',[VcardController::class, 'getPiggyBankBalance'])->middleware('can:view,vcard');

    Route::post('vcards/{vcard}/piggyBankOperation',[VcardController::class, 'piggyBankOperation'])->middleware('can:update,vcard');


    /*************************************** Transactions ***************************************/

    Route::post('transactions', [TransactionController::class, 'storeTransaction'])->middleware('can:create,App\Models\Transaction');

    Route::get('transactions', [TransactionController::class, 'getTransactions'])->middleware('can:viewAny,App\Models\Transaction');

    Route::get('transactions/{transaction}', [TransactionController::class, 'getTransaction'])->middleware('can:view,transaction');

    Route::put('transactions/{transaction}', [TransactionController::class, 'updateTransaction'])->middleware('can:update,transaction');

    /*************************************** Statistics ***************************************/


    Route::get('statistics/transactionsPaymentType', [StatisticController::class, 'getTransactionsPaymentType']);

    Route::get('statistics/transactionsType', [StatisticController::class, 'getTransactionType']);

    Route::get('statistics/categoriesType', [StatisticController::class, 'getCategoriesType']);

    Route::get('statistics/transactionsPaymentTypeValue', [StatisticController::class, 'getCategoriesPaymentTypeValue']);


   /*************************************** Logout ***************************************/

    Route::post('/logout', [AuthController::class, 'logout']);
});

/*************************************** Login ***************************************/

Route::post('/login', [AuthController::class, 'login']);

/*************************************** Create vcard ***************************************/
Route::post('vcards', [VcardController::class, 'storeVcard']);
