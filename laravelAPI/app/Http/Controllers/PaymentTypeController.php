<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentTypesRequest;
use App\Http\Resources\PaymentTypesResource;
use App\Models\PaymentTypes;
use Illuminate\Http\Request;

class PaymentTypeController extends Controller
{
    public function getPaymentsTypes(){
        return  PaymentTypesResource::Collection(PaymentTypes::all());
    }

    public function getPaymentType(PaymentTypesRequest $payment_type){

        $payment_type = PaymentTypes::where('code', $payment_type->query('code'))->first();

        //return new PaymentTypesResource($payment_type);
    }
}
