<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function get()
    {
        $transactions = Transaction::all();
        return TransactionResource::collection($transactions);
    }

    public function getVcardTransactions(Transaction $transaction)
    {
        return new TransactionResource($transaction);
    }
}
