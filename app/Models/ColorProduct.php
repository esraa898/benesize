<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ColorProduct extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $table='color_product';

    protected $fillable=[
        'product_id','color_id',
    ];

    public function color(){
     return $this->belongsTo(Color::class,'color_id');
    }

    public function product(){
        return $this->belongsTo(Product::class,'product_id')->with('productOffer');
    }

    public function productColorSizes(){
        return $this->hasMany(ProductColorSize::class,'color_product_id')->with('size');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')
                    ->width(100)
                    ->height(100);
            });
    }
}
