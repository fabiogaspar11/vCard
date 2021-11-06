<?php

namespace App\Http\Controllers;

use App\Http\Requests\VcardRequest;
use App\Models\Vcard;
use Illuminate\Http\Request;
use App\Http\Resources\VcardResource;

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

    public function store(VcardRequest $request)
    {
        $validated_data = $request->validated();
        Vcard::create($validated_data);
        return new VcardResource($request);
    }

    public function update(VcardRequest $request, Vcard $vcard)
    {
        $phone_number = $vcard->phone_number;
        $vcard->fill($request->validated());
        $vcard->phone_number = $phone_number;
        $vcard->save();
        return new VcardResource($request);
    }

    public function destroy(Vcard $vcard){
        $vcard->delete();
        return new VcardResource($vcard);
    }
}
