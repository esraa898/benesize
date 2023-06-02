<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        return $this->belongsTo(Product::class,'product_id');
    }
}
