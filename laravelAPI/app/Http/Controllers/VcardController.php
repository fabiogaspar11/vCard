<?php

namespace App\Http\Controllers;

use App\Models\Vcard;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\VcardRequest;
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

    public function storeVcard(VcardRequest $request)
    {
        if (!isset($request->phone_number)) {
            throw ValidationException::withMessages(['phone_number' => 'Phone number is mandatory']);
        }
        $request->validate([
            'email' => 'regex:/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/'
        ]);
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
        $vcard->save();
        return new VcardResource($vcard);
    }

    public function updateVcard(VcardRequest $request, Vcard $vcard)
    {
        $phone_number = $vcard->phone_number;
        $vcard->fill($request->validated());
        $vcard->phone_number = $phone_number;
        $vcard->save();
        return new VcardResource($request);
    }

    public function destroyVcard(Vcard $vcard){
        $vcard->delete();
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
        $transactions = $vcard->transactions;
        if($transactions->isEmpty()){
            return [
                "error"=> "This vcard doesn't have any transactions yet"
            ];
        }
        return TransactionResource::collection($transactions);
    }
}
