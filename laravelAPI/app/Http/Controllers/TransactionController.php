<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Vcard;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\TransactionPut;
use App\Http\Requests\TransactionPost;
use App\Http\Resources\TransactionResource;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    public function getTransactions()
    {
        $transactions = Transaction::all();
        return TransactionResource::collection($transactions);
    }

    public function getVcardTransactions(Transaction $transaction)
    {
        return new TransactionResource($transaction);
    }

    //Private function
    private function updateNewOldBalance($value,$balance, $transaction){
         $transaction->old_balance = $balance;
         if($transaction->type == 'D'){
             $transaction->new_balance = $transaction->old_balance - $value;
         }else{
             $transaction->new_balance = $transaction->old_balance + $value;
         }
    }
    private function verifyCategoryBelongsToVcard(TransactionPost $request){
        if(isset($request->category_id)){
            $category = Category::find($request->category_id);
            if($category->vcard != $request->vcard){
                throw ValidationException::withMessages(['category' => "This category doesn't belong to the vcard"]);
            }
        }
    }
    private function verifyCategoryBelongsToVcardPUT(TransactionPut $request){
        if(isset($request->category_id)){
            $category = Category::find($request->category_id);
            if($category->vcard != $request->vcard){
                throw ValidationException::withMessages(['category' => "This category doesn't belong to the vcard"]);
            }
        }
    }
    public function storeTransaction(TransactionPost $request)
    {
        $validated_data = $request->validated();
        $this->verifyCategoryBelongsToVcard($request);

        if($request->pair_vcard == $request->vcard){
            throw ValidationException::withMessages(['pair_vcard' => 'Recipient vcard cannot be the same as the sender vcard']);
        }
        $Begintransaction = new Transaction();
        $Begintransaction->fill($validated_data);
        //update new and old balances
        $value = $Begintransaction->value;
        $vcard = Vcard::find($request->vcard);
        $balance = $vcard->balance;
        if($balance < $value){

            throw ValidationException::withMessages(['value' => 'Balance is insufficient']);
        }
        $this->updateNewOldBalance($value, $balance, $Begintransaction);


        //Update vcard balance
        $vcard->balance = $Begintransaction->new_balance;
        $isVCARDTransaction = $Begintransaction->payment_type == "VCARD";
        if($isVCARDTransaction){
            $Endtransaction = new Transaction();
            $Endtransaction->fill($validated_data);
            //Vcards and Pair vcards
            $Endtransaction->vcard = $Begintransaction->pair_vcard;
            $Endtransaction->pair_vcard = $Begintransaction->vcard;
            //type of transaction
            $Endtransaction->type = $Begintransaction->type == 'C' ? 'D' : 'C';
            //update new and old balances
            $pairVcard = Vcard::find($request->pair_vcard);
            $balancePairVcard = $pairVcard->balance;
            $this->updateNewOldBalance($value, $balancePairVcard, $Endtransaction);
            //Update vcard balance
            $pairVcard->balance = $Endtransaction->new_balance;
        }else{
            $Begintransaction->pair_vcard = null;
        }

        $datetime = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $Begintransaction->date = $date;
        $Begintransaction->datetime = $datetime;
        $Endtransaction->date = $date;
        $Endtransaction->datetime = $datetime;

        try {
          DB::beginTransaction();

            $vcard->save();

            if($isVCARDTransaction){
                $pairVcard->save();
                $Begintransaction->save();
                $Endtransaction->save();

                $Begintransaction->pair_transaction = $Endtransaction->id;
                $Endtransaction->pair_transaction = $Begintransaction->id;
                $Endtransaction->save();
            }

            $Begintransaction->save();
          DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception("Error creating the transaction");
        }

        return new TransactionResource($Begintransaction);
    }

    public function updateTransaction(TransactionPut $request, Transaction $transaction){

        $this->verifyCategoryBelongsToVcardPUT($request);

        $validated_data = $request->validated();
        $transaction->fill($validated_data);
        $transaction->save();
        return new TransactionResource($transaction);
    }

}
