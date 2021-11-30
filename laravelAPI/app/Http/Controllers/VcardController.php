<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Vcard;
use App\Models\Category;
use Illuminate\Support\Str;

use App\Http\Requests\VcardPut;

use App\Http\Requests\VcardPhoto;

use App\Models\DefaultCategory;
use App\Http\Requests\VcardPost;
use App\Http\Requests\VcardDelete;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\VcardResource;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

use Image;

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
}
