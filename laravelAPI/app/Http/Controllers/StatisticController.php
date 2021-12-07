<?php

namespace App\Http\Controllers;

use Image;
use Exception;
use App\Models\Vcard;
use App\Models\Category;

use Illuminate\Support\Str;

use Illuminate\Http\Request;
use App\Http\Requests\VcardPut;
use App\Models\DefaultCategory;
use App\Http\Requests\VcardPost;
use App\Http\Requests\VcardDelete;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\VcardResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Nette\Utils\Json;

class StatisticController extends Controller
{
    public function getCategoriesType(User $user){
        if (auth()->user()->user_type == 'V'){
            $sql = Category::select(DB::raw('type, COUNT(type) as count '))
                                ->where('vcard', $user->username)
                                ->groupBy('type')
                                ->get();
        }
        else if (auth()->user()->user_type == 'A'){
            $sql = Category::select(DB::raw('type, COUNT(type) as count '))
                                ->groupBy('type')
                                ->get();
        }
        return $sql;
    }

    public function getTransactionsPaymentType(User $user){
        if (auth()->user()->user_type == 'V'){
            $sql = Transaction::select(DB::raw('payment_type, COUNT(payment_type) as count '))
                                ->where('vcard', $user->username)
                                ->groupBy('payment_type')
                                ->get();
        }
        else if (auth()->user()->user_type == 'A'){
            $sql = Transaction::select(DB::raw('payment_type, COUNT(payment_type) as count '))
                                ->groupBy('payment_type')
                                ->get();
        }

        return $sql;
    }

    public function getTransactionType(User $user){
        if (auth()->user()->user_type == 'V'){
            $sql = Transaction::select(DB::raw('type, COUNT(type) as count '))
                                ->where('vcard', $user->username)
                                ->groupBy('type')
                                ->get();
        }
        else if (auth()->user()->user_type == 'A'){
            $sql = Transaction::select(DB::raw('type, COUNT(type) as count '))
                                ->groupBy('type')
                                ->get();
        }
        return $sql;
    }

    public function getCategoriesPaymentTypeValue(User $user){
        if (auth()->user()->user_type == 'V'){
            $sql = Transaction::select(DB::raw('payment_type, Sum(value) as Value'))
                                ->where('vcard', $user->username)
                                ->groupBy('payment_type')
                                ->get();
        }
        else if (auth()->user()->user_type == 'A'){
            $sql = Transaction::select(DB::raw('payment_type, Sum(value) as Value'))
                                ->groupBy('payment_type')
                                ->get();
        }
        return $sql;
    }
}
