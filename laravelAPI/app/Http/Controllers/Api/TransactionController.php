<?php

namespace App\Http\Controllers\Api;
use Exception;
use App\Models\Vcard;
use App\Models\Category;
use App\Models\PaymentType;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
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

    public function getTransaction(Transaction $transaction)
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
    private function verifyCategoryToVcard(TransactionPost $request){
        if(isset($request->category_id)){
            $category = Category::find($request->category_id);
            if($category == null){
                throw ValidationException::withMessages(['category' => "This category doesn't exists"]);
            }
            if($category->vcard != $request->vcard){
                throw ValidationException::withMessages(['category' => "This category doesn't belong to the vcard"]);
            }
            if($category->type != $request->type){
                throw ValidationException::withMessages(['category' => "This category type doesnt match the transaction type"]);
            }
        }
    }
    private function verifyCategoryBelongsToVcardPUT(TransactionPut $request, Transaction $transaction){
        if(isset($request->category_id)){
            $category = null;
            $category = Category::find($request->category_id);
            if($category == null){
                throw ValidationException::withMessages(['category' => "This category doesn't exists"]);
            }
            $vcard = Vcard::find($transaction->vcard);
            if($category->vcard != $vcard->phone_number){
                return 0;
            }
            if($category->type != $transaction->type){
                return -1;
            }
        }
        return 1;
    }

    public function storeTransaction(TransactionPost $request)
    {
       if(auth()->user()->user_type == 'V'){
            $this->verifyCategoryToVcard($request);
       }
        $paymentTypeFound = PaymentType::find($request->payment_type);
        $rule = json_decode($paymentTypeFound->validation_rules)->validation;
        if($request->payment_type == 'VCARD'){
            $request->validate([
                'pair_vcard' => 'required',
                'payment_reference' => [$rule, 'required']
            ],
            ['payment_reference.required' => 'Payment reference is mandatory',
            'payment_reference.regex' => 'Payment reference is not valid',
            'payment_reference.same' => 'Payment reference is not valid',
            'payment_reference.email' => 'Payment reference is not valid',
            'pair_vcard.required' => 'Pair vcard is mandatory'
            ]);
        }else{
            $request->validate([
                    'payment_reference' => [$rule, 'required'],
                ],
                ['payment_reference.required' => 'Payment reference is mandatory',
                'payment_reference.regex' => 'Payment reference is not valid',
                'payment_reference.same' => 'Payment reference is not valid',
                'payment_reference.email' => 'Payment reference is not valid',
            ]);
        }

        $validated_data = $request->validated();

        if($request->pair_vcard == $request->vcard){
            throw ValidationException::withMessages(['pair_vcard' => 'Recipient vcard cannot be the same as the sender vcard']);
        }

        $Begintransaction = new Transaction();
        $Begintransaction->fill($validated_data);

        $vcard = Vcard::find($request->vcard);
        if ($vcard->blocked == 1){
            throw ValidationException::withMessages(['vcard' => 'Recipient vcard is blocked']);
        }
        if(isset($request->confirmation_code) && !Hash::check($request->confirmation_code, $vcard->confirmation_code)){
            throw ValidationException::withMessages(['confirmation_code' => "PIN is invalid"]);
        }
        //update new and old balances


        $balance = $vcard->balance;
        $value = number_format($Begintransaction->value,2, '.', '');


        $difference = 0;
        $roundedValue = 0;
        if($vcard->custom_data != null){
            $difference = ceil($value) - $Begintransaction->value;
            $roundedValue = ceil($value);
        }
        if(auth()->user()->user_type=='V' && $request->type == 'C'){
            throw ValidationException::withMessages(['value' => 'Vcard cannot make credit transactions']);
        }

        if($request->type == 'D' && $balance < $value){
            throw ValidationException::withMessages(['value' => 'Balance is insufficient']);
        }
        if($request->type == 'D' && $request->value > $vcard->max_debit){
            throw ValidationException::withMessages(['value' => 'Value to debit cannot be bigger than the max debit ' . $vcard->max_debit]);
        }
        $this->updateNewOldBalance($value, $balance, $Begintransaction);


        //Update vcard balance
        $vcard->balance = $Begintransaction->new_balance;

        if($difference>0 && $balance > $roundedValue){
            $vcard->balance -= $difference;
            $piggyBank = json_decode($vcard->custom_data);
            $currentBalance = $piggyBank->balance;
            $piggyBank = array();
            $piggyBank["balance"] = number_format($currentBalance + $difference,2, '.', '');
            $json = json_encode($piggyBank);
            $vcard->custom_data =  $json;
        }
        $isVCARDTransaction = $Begintransaction->payment_type == "VCARD";
        if($isVCARDTransaction){
            $Endtransaction = new Transaction();
            $Endtransaction->fill($validated_data);
            //Vcards and Pair vcards
            $Endtransaction->vcard = $Begintransaction->pair_vcard;
            $Endtransaction->payment_reference = $Begintransaction->vcard;
            $Endtransaction->pair_vcard = $Begintransaction->vcard;
            //type of transaction
            $Endtransaction->type = $Begintransaction->type == 'C' ? 'D' : 'C';
            //update new and old balances
            $pairVcard = Vcard::find($request->pair_vcard);
            if( $pairVcard  == null){
                throw ValidationException::withMessages(['pair_vcard' => 'Recipient vcard not found']);
            }
            if ($pairVcard->blocked == 1){
                throw ValidationException::withMessages(['pair_vcard' => 'Recipient vcard is blocked']);
            }
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

        if($isVCARDTransaction){
            $Endtransaction->date = $date;
            $Endtransaction->datetime = $datetime;
        }

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
            throw ValidationException::withMessages(['default' => 'Error creating the transaction']);
        }

        return new TransactionResource($Begintransaction);
    }

    public function updateTransaction(TransactionPut $request, Transaction $transaction){
        if(auth()->user()->user_type == 'V'){
            $result  =$this->verifyCategoryBelongsToVcardPUT($request, $transaction);
            if($result == 0){
                throw ValidationException::withMessages(['category_id' => "This category doesn't belong to the vcard"]);
            }

            if($result = -1){
                throw ValidationException::withMessages(['category' => "This category type doesnt match the transaction type"]);
            }
       }
        $validated_data = $request->validated();
        $transaction->fill($validated_data);
        $transaction->save();
        return new TransactionResource($transaction);
    }

}
