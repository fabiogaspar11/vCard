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
use Illuminate\Validation\ValidationException;
use Nette\Utils\Json;

class VcardController extends Controller
{
    public function getVcards()
    {
        $allVcards = Vcard::all();
        return VcardResource::collection($allVcards);
    }

    public function checkVcard(int $vcard){
        $vcardFound = Vcard::find($vcard);
        return $vcardFound == null ? ["response" => "No"] : ["response" =>  "Yes"] ;
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
            $request->file('photo_url')->storeAs('fotos', $filename);
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
        $old_photo = $vcard->photo_url;
        if($request->old_password != null){
            if(!Hash::check($request->old_password, $vcard->password)){
                throw ValidationException::withMessages(['password' => "Password is not correct"]);
            }
            if($request->old_password == $request->password){
                throw ValidationException::withMessages(['password' => "Old password and new password must be different"]);
            }
        }
        else if ($request->password != null || $request->confirmation_code != null){
            throw ValidationException::withMessages(['password' => "You must insert the old password first"]);
        }


        $validated_data = $request->validated();
        $vcard->fill($validated_data);

        $filename = $vcard->phone_number . "_" . Str::random(6) . '.jpg';

        if ($request->hasFile('photo_url')) {
            Storage::disk('local')->delete('public/fotos/'. $old_photo);
            $request->file('photo_url')->storeAs('fotos', $filename);
            $vcard->photo_url = $filename;
        }

        $vcard->password = Hash::make($vcard->password);
        $vcard->confirmation_code = Hash::make($vcard->confirmation_code);
        $vcard->save();
        return new VcardResource($vcard);
    }
    public function alterBlock(Vcard $vcard){
        $vcard->blocked = $vcard->blocked == 1 ? 0 : 1;
        $vcard->save();
        return new VcardResource($vcard);
    }

    public function alterDebitLimit(VcardPut $request,Vcard $vcard){
        if(!isset($request->max_debit)){
            throw ValidationException::withMessages(['max_debit' => "Max debit is mandatory"]);
        }
        $vcard->max_debit = $request->max_debit;
        $vcard->save();
        return new VcardResource($vcard);
    }

    public function destroyVcard(VcardDelete $request, Vcard $vcard){
        if( auth()->user()->user_type != 'A'){
            if(!isset($request->password)){
                throw ValidationException::withMessages(['password' => "Password is mandatory"]);
            }
            if(!isset($request->confirmation_code)){
                throw ValidationException::withMessages(['confirmation_code' => "PIN is mandatory"]);
            }
            if(!Hash::check($request->password, $vcard->password)){
                throw ValidationException::withMessages(['password' => "Password is not correct"]);
            }
            if(!Hash::check($request->confirmation_code, $vcard->confirmation_code)){
                throw ValidationException::withMessages(['confirmation_code' => "PIN is not correct"]);
            }
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

    public function getVcardPhoto(Vcard $vcard){

        $photo = $vcard->photo_url;
        return Storage::disk('local')->get('public/fotos/'.$photo);
    }

    public function getVcardCategories(Vcard $vcard){
        $categories = $vcard->categories;
        if($categories->isEmpty()){
            return response()->json([
                'error' => 'This vcard does not have any categories yet'
            ], 404);
        }
        return CategoryResource::collection($categories);
    }

    public function getVcardCategoriesType(Vcard $vcard){
        $sql = Category::select(DB::raw('type, COUNT(type) as count '))
            	            ->where('vcard', $vcard->phone_number)
                            ->groupBy('type')
                            ->get();
        return $sql;
    }


    public function filterVcards(Request $request){

        $state = $request->has('state') == false ? null : $request->input("state");
        $phone_number = $request->has('phone_number') == false ? null : $request->input('phone_number');
        $name = $request->has('name') == false ? null : $request->input('name');
        $query = Vcard::query();
        if($state != null) {
            $query->where("blocked", '=', $state);
        }
        if($phone_number != null) {
            $query->where('phone_number','=', $phone_number);
        }
        if($name != null) {
            $query->where('name','=',$name);
        }
        $vcards = $query->get();

        return VcardResource::collection($vcards);
    }

    public function getVcardTransactionsPaymentType(Vcard $vcard){
        $sql = Transaction::select(DB::raw('payment_type, COUNT(payment_type) as count '))
            	            ->where('vcard', $vcard->phone_number)
                            ->groupBy('payment_type')
                            ->get();
        return $sql;
    }

    public function getVcardTransactionType(Vcard $vcard){
        $sql = Transaction::select(DB::raw('type, COUNT(type) as count '))
            	            ->where('vcard', $vcard->phone_number)
                            ->groupBy('type')
                            ->get();
        return $sql;
    }

    public function getVcardCategoriesPaymentTypeValue(Vcard $vcard){
        $sql = Transaction::select(DB::raw('payment_type, Sum(value) as Value'))
            	            ->where('vcard', $vcard->phone_number)
                            ->groupBy('payment_type')
                            ->get();
        return $sql;
    }


    public function piggyBankState(Vcard $vcard){
        return $vcard->custom_data == null ? ["response" => false] : ["response" => true];
    }

    public function getPiggyBankBalance(Vcard $vcard){
        return $vcard->custom_data;
    }


    public function createPiggyBank(Vcard $vcard){
        if($vcard->custom_data != null)
            return "Vcard already has a piggy bank";

        $piggyBank = array();
        $piggyBank["balance"] = 0;

        $json = json_encode($piggyBank);
        $vcard->custom_data =  $json;
        $vcard->save();
        return $piggyBank["balance"];
    }



    public function piggyBankOperation(Vcard $vcard, Request $request){
        $piggyBank = json_decode($vcard->custom_data);
        $currentBalance = $piggyBank->balance;

        if($request->type == 'C'){
            $newBalance = $currentBalance + $request->amount;
        }
        if($request->type == 'D'){
            $newBalance = $currentBalance - $request->amount;
        }

        $newPiggyBank = array();
        $newPiggyBank["balance"] = $newBalance;

        $json = json_encode($newPiggyBank);
        $vcard->custom_data = $json;
        $vcard->save();

        return $newBalance;
    }


}
