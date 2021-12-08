<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentTypeResource;
use App\Models\PaymentType;

class PaymentTypeController extends Controller
{
    public function getPaymentsTypes(){
        $paymentTypes = PaymentType::all();
        return  PaymentTypeResource::collection($paymentTypes);
    }

}
