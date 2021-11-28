<?php

namespace App\Http\Resources;

use App\Models\Vcard;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if($this->pair_vcard){
            $pair_vcard = Vcard::find($this->pair_vcard);
        }
        else{
            $pair_vcard = [];
        }
        return [
            "id"=>$this->id,
            "vcard" => $this->vcard,
            "date" => $this->date,
            "datetime" => $this->datetime,
            "type" => $this->type,
            "value" => $this->value,
            "old_balance" => $this->old_balance,
            "new_balance" => $this->new_balance,
            "payment_type" => $this->payment_type,
            "payment_reference" => $this->payment_reference,
            "pair_transaction" => $this->pair_transaction,//$this->pair_transaction ? new TransactionResource($this->pair_transaction) : [],
            "pair_vcard" => $pair_vcard != [] ? new VcardResource($pair_vcard) : $pair_vcard,
            "category_id" => $this->category,
            "description" => $this->description,
        ];
    }
}
