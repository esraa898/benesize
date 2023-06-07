<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
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
            'id' => $this->id,
            'image'=> asset('assets/images/'.$this->image),
            'title' => $this->title,
            'type' => $this->type,
            'type_id' => $this->type_id,
            'sort' => $this->sort,
        ];
    }
}
