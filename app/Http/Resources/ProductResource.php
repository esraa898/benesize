<?php

namespace App\Http\Resources;

use App\Http\Resources\ProductColorsResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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

            "id"             => $this->id,
            "name"           => $this->name,
            "description"    => $this->description,
            "min_price"      => $this->min_price,
            "max_price"      => $this->max_price,
            'color_count'    => $this->colors->count(),
            'category'       => $this->category->name,
            'colors'         => ProductColorsResource::collection($this->colors),
            'sizes'          => ProductSizesResource::collection($this->sizes),

        ];
    }
}
