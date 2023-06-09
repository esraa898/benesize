<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $images = null;
        $imageMedias = $this->product->getMedia('images', ['color_id' => $this->color_id]);
        foreach($imageMedias as $imageMedia){
            $images [] = $imageMedia->getUrl();
        }

         return [

            'id'             => $this->id,
            'name'           => $this->product->name,
            'description'    => $this->product->description,
            'price'          => $this->product->price,
            'min_price'      => $this->product->min_price,
            'repeat_times'   => $this->product->repeat_times,
            'increase_ratio' => $this->product->increase_ratio,
            'max_price'      => $this->product->min_price + ($this->product->increase_ratio * ($this->product->repeat_times+1)),
            'images'         => $images,
            'product_color_size'=> ProductColorSizeResource::collection($this->productColorSizes),

        ];
    }
}
