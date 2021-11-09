<?php

namespace App\Http\Controllers;

use App\Models\Vcard;
use Illuminate\Http\Request;
use App\Http\Requests\VcardRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\VcardResource;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use function PHPUnit\Framework\isNull;

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
        $validated_data = $request->validated();
        $vcard = new Vcard;
        $vcard->fill($validated_data);
        if (is_null($vcard->phone_number)) {
            throw ValidationException::withMessages(['phone_number' => 'Phone number is mandatory']);
        }

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
}
