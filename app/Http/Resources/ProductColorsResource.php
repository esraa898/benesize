<?php

namespace App\Http\Resources;

use App\Models\Color;
use App\Models\ProductColorSize;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductColorsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $image = null;
        $imageMedia = $this->product->getFirstMedia('images', ['color_id' => $this->color_id]);
        if ($imageMedia) {
            $image = $imageMedia->getUrl();
        }
        return  [
            'id'                    => $this->product->id,
            "color_id"              => $this->color_id,
            "hexa"                  => $this->color->hexa,
            'name'                  => $this->product->name,
            'description'           => $this->product->description,
            'price'                 => $this->product->price,
            'min_price'             => $this->product->min_price,
            'repeat_times'          => $this->product->repeat_times,
            'increase_ratio'        => $this->product->increase_ratio,
            'max_price'             => $this->product->min_price + ( $this->product->increase_ratio * ($this->product->repeat_times + 1) ),
            'image'                 => $image,
            "is_new"                => $this->product->is_new,
            "is_on_sale"            => $this->product->is_on_sale,
            "is_new_arrival"        => $this->product->is_new_arrival,
            "is_best_seller"        => $this->product->is_best_seller,
            'offer'                 => new ProductResource($this->product->ProductOffer),
            'color_count'           => $this->product->colors->count(),
            'product_color_size'    => ProductColorSizeResource::collection($this->productColorSizes),
            'default'               => $this->get_defaults()

        ];

    }

    public function get_defaults(){

        $first_color = Color::first();
        $first_color_id = $first_color->id;
        $image = null;
        $imageMedia = $this->product->getFirstMedia('images', ['color_id' =>$first_color_id]);
        if ($imageMedia) {
            $image = $imageMedia->getUrl();
        }
        $defaults = [
            'color' => $first_color_id,
            'image' => $image
        ];
        return $defaults;
    }
}
