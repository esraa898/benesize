<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubCategory extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name'];

    public function products ()
    {
        return $this->hasMany(Product::class,'sub_category_id');
    }

}
