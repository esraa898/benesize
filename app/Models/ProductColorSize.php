<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductColorSize extends Model
{
    use HasFactory;

    protected $fillable=[
       'color_product_id','size_id'
    ];

    public function colorProduct(){
        return $this->belongsTo(ColorProduct::class,'color_product_id');
    }
    public function size(){
        return $this->belongsTo(Size::class,'size_id');
    }
}
