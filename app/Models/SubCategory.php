<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class SubCategory extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['id', 'name'];

    public function products ()
    {
        return $this->hasMany(Product::class,'sub_category_id');
    }
    
    public function category() {
        return $this->belongsTo(Category::class);
    }
}
