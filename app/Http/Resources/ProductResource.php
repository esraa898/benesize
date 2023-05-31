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
            'price'          => $this->price,
            "min_price"      => $this->min_price,
            "max_price"      => $this->max_price,
            "is_new"         => $this->is_new,
            "is_on_sale"     => $this->is_on_sale,
            "is_new_arrival" => $this->is_new_arrival,
            "is_best_seller" => $this->is_best_seller,
            'color_count'    => $this->colors->count(),
            'sub_category'   => new SubCategoryResource($this->subCategory),
            'colors'         => ProductColorsResource::collection($this->colors),
            'sizes'          => ProductSizesResource::collection($this->sizes),

        ];
    }
}
