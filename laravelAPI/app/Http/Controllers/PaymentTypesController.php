<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaymentsTypesResource;
use App\Models\PaymentTypes;
use Illuminate\Http\Request;

class PaymentTypesController extends Controller
{
    public function getPaymentsTypes(){
        return  PaymentsTypesResource::Collection(PaymentTypes::all());
    }

    public function getPaymentType(PaymentTypes $payment_types){
        return new PaymentsTypesResource($payment_types);
    }
}
