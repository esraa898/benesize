<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public static $rules = [
        'name' => 'required|max:100',
        'description' => 'required|string',
        'min_price' => 'required|numeric|min:0',
        'max_price' => 'required|numeric|min:0',
        'category_id' => 'required|exists:categories, id',
    ];

    protected $fillable = [
        'name',
        'description',
        'min_price',
        'category_id',
        'max_price',
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function sizes(){
        return $this->belongsToMany(Size::class);
    }

    public function colors(){
        return $this->belongsToMany(Color::class);
    }
}
