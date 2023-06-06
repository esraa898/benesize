<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id'=>$this->id,
            'name'=>$this->name,
            'store_name'=>$this->seller? $this->seller->store_name:null,
            'wallet_number'=>$this->seller?$this->seller->wallet_number:null,
            'email'=>$this->email,
            'phone'=>$this->phone,
            'image'=>$this->getFirstMedia('images') != null ? $this->getFirstMedia('images')->getUrl() : null,
            'address'=>$this->address,
            'lat'=>$this->lat,
            'lang'=>$this->lang,
            'gender' => $this->gender,
            'date_of_birth'=>$this->date_of_birth,
            'is_registerd'=>$this->is_registerd,
            'area'=>new AreaResource($this->area),

        ];
    }
}
