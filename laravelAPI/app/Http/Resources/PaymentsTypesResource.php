<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentsTypesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "code" => $this->code,
            "name" => $this->name,
            "description" => $this->description,
            "validation_rules" => $this->validation_rules,
            "custom_options" => $this->custom_options,
            "custom_data" => $this->custom_data
        ];
    }
}
