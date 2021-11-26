<?php

namespace App\Http\Controllers;

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
use App\Http\Resources\TransactionResource;
use Illuminate\Validation\ValidationException;

class VcardController extends Controller
{
    public function getVcards()
    {
        $allVcards = Vcard::all();
        return VcardResource::collection($allVcards);
    }
    public function getVcard(Vcard $vcard)
    {
        return new VcardResource($vcard);
    }

    public function storeVcard(VcardPost $request)
    {
        $validated_data = $request->validated();
        $vcard = new Vcard();
        $vcard->fill($validated_data);
        $filename = $vcard->phone_number . "_" . Str::random(6) . '.jpg';

        if ($request->hasFile('photo_url')) {
            $request->file('photo_url')->storeAs('public/fotos', $filename);
            $vcard->photo_url = $filename;
        }

        $vcard->max_debit = 5000.00;
        $vcard->balance = 0.00;
        $vcard->blocked = 0;
        $vcard->password = Hash::make($vcard->password);
        $vcard->confirmation_code = Hash::make($vcard->confirmation_code);

        try{
            $vcard->save();

            $categoriesDefault = DefaultCategory::all();
            foreach($categoriesDefault as $categoryDef){
                $category = new Category();
                $category->vcard = $vcard->phone_number;
                $category->type = $categoryDef->type;
                $category->name = $categoryDef->name;
                $category->save();
            }
        }catch(Exception $e){
            DB::rollback();
            throw new Exception("Error creating the vcard");
        }
        return new VcardResource($vcard);
    }

    public function updateVcard(VcardPut $request, Vcard $vcard)
    {

        $validated_data = $request->validated();
        $vcard->fill($validated_data);
        $vcard->save();
        return new VcardResource($vcard);
    }

    public function destroyVcard(VcardDelete $request, Vcard $vcard){

        if(!Hash::check($vcard->password,Hash::make($vcard->password))){
            throw ValidationException::withMessages(['password' => "Password is not correct"]);
        }
        if(!Hash::check($vcard->confirmation_code,Hash::make($vcard->confirmation_code))){
            throw ValidationException::withMessages(['confirmation_code' => "PIN is not correct"]);
        }
        if($vcard->balance > 0){
            throw ValidationException::withMessages(['balance' => "Vcard cannot be deleted - Balance is bigger than 0.00"]);
        }
        $transactions = $vcard->transactions;
        $numberTransactions = count($transactions);
        try{
            if($numberTransactions > 0){
                foreach($transactions as $transaction){
                    $transaction->delete();
                }
                $vcard->delete();
            }
            if($numberTransactions == 0){
                $categories = $vcard->categories;
                foreach($categories as $category){
                    $category->forceDelete();
                }
                $vcard->forceDelete();
            }

        }catch(Exception $e){
            DB::rollback();
            throw new Exception("Error deleting the vcard");
        }

        return new VcardResource($vcard);
    }


    public function getVcardLastTransaction(Vcard $vcard)
    {
        $transactions = $vcard->transactions;
        if($transactions->isEmpty()){
            return [
                "error"=> "This vcard doesn't have any transactions yet"
            ];
        }
        $transaction =  $transactions->sortByDesc('datetime')->first();
        return new TransactionResource($transaction);
    }

    public function getVcardTransactions(Vcard $vcard){
        $transactions = $vcard->transactions->sortByDesc('datetime');
        if($transactions->isEmpty()){
            return [
                "error"=> "This vcard doesn't have any transactions yet"
            ];
        }
        return TransactionResource::collection($transactions);
    }
}
