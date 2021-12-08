<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\User;


class StatisticController extends Controller
{
    public function getCategoriesType(){
        $userLogged = auth()->user();
        if ($userLogged->user_type == 'V'){
            $sql = Category::select(DB::raw('type, COUNT(type) as count '))
                                ->where('vcard', $userLogged->username)
                                ->groupBy('type')
                                ->get();
        }
        else{
            $sql = Category::select(DB::raw('type, COUNT(type) as count '))
                                ->groupBy('type')
                                ->get();
        }
        return $sql;
    }

    public function getTransactionsPaymentType(User $user){
        $userLogged = auth()->user();
        if ($userLogged ->user_type == 'V'){
            $sql = Transaction::select(DB::raw('payment_type, COUNT(payment_type) as count '))
                                ->where('vcard', $userLogged->username)
                                ->groupBy('payment_type')
                                ->get();
        }
        else{
            $sql = Transaction::select(DB::raw('payment_type, COUNT(payment_type) as count '))
                                ->groupBy('payment_type')
                                ->get();
        }

        return $sql;
    }

    public function getTransactionType(User $user){
        $userLogged = auth()->user();
        if ($userLogged->user_type == 'V'){
            $sql = Transaction::select(DB::raw('type, COUNT(type) as count '))
                                ->where('vcard', $userLogged->username)
                                ->groupBy('type')
                                ->get();
        }
        else{
            $sql = Transaction::select(DB::raw('type, COUNT(type) as count '))
                                ->groupBy('type')
                                ->get();
        }
        return $sql;
    }

    public function getCategoriesPaymentTypeValue(User $user){
        $userLogged = auth()->user();
        if ($userLogged->user_type == 'V'){
            $sql = Transaction::select(DB::raw('payment_type, Sum(value) as Value'))
                                ->where('vcard', $userLogged->username)
                                ->groupBy('payment_type')
                                ->get();
        }
        else{
            $sql = Transaction::select(DB::raw('payment_type, Sum(value) as Value'))
                                ->groupBy('payment_type')
                                ->get();
        }
        return $sql;
    }
}
