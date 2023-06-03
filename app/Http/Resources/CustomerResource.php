<?php

namespace App\Http\Resources;

use App\Http\Resources\AreaResource;
use App\Http\Resources\CityResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'name' => $this->name,
            'address' => $this->address,
            'phone_number' => $this->phone_number,
            'second_phone_number' => $this->second_phone_number,
            'area'=>new AreaResource($this->area)
        ];
    }
}
