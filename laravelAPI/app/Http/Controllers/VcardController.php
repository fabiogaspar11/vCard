<?php

namespace App\Http\Controllers;

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

}
