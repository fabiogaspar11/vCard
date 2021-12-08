<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\Administrator;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AdministratorPost;
use App\Http\Resources\AdministratorResource;

use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Transaction;

class AdministratorController extends Controller
{
    public function getAdministrator(Administrator $admin)
    {
        return new AdministratorResource($admin);
    }

    public function getAdministrators()
    {
        $allAdministrators = Administrator::all()->except(auth()->user()->id);
        return AdministratorResource::collection($allAdministrators);
    }

    public function storeAdministrator(AdministratorPost $request)
    {
        $admin = new Administrator();
        $admin->fill($request->validated());
        $admin->password = Hash::make($request->password);
        $admin->remember_token = Str::random(10);
        $admin->save();
        return new AdministratorResource($admin);
    }

    public function destroyAdministrator(Administrator $admin){
        $admin->delete();
        return new AdministratorResource($admin);
    }



    public function getAllCategoriesType(){
        $sql = Category::select(DB::raw('type, COUNT(type) as count '))
                            ->groupBy('type')
                            ->get();
        return $sql;
    }

    public function getAllTransactionsPaymentType(){
        $sql = Transaction::select(DB::raw('payment_type, COUNT(payment_type) as count '))
                            ->groupBy('payment_type')
                            ->get();
        return $sql;
    }

    public function getAllTransactionType(){
        $sql = Transaction::select(DB::raw('type, COUNT(type) as count '))
                            ->groupBy('type')
                            ->get();
        return $sql;
    }

    public function getAllCategoriesPaymentTypeValue(){
        $sql = Transaction::select(DB::raw('payment_type, Sum(value) as Value'))
                            ->groupBy('payment_type')
                            ->get();
        return $sql;
    }
}
