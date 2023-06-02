<?php

namespace App\Models;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Product extends Model implements HasMedia {
    use InteractsWithMedia;
    use HasFactory;

    public static $rules = [
        'name' => 'required|max:100',
        'description' => 'required|string',
        'min_price' => 'required|numeric|min:0',
        'max_price' => 'required|numeric|min:0',
        'price' => 'required|numeric|min:0',
        'sub_category_id' => 'required|exists:sub_categories, id',
    ];

    protected $fillable = [
        'name',
        'description',
        'min_price',
        'sub_category_id',
        'max_price',
        'price',
        'is_new',
        'is_on_sale',
        'is_new_arrival',
        'is_best_seller'
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }
    public function subCategory() {
        return $this->belongsTo(SubCategory::class);
    }
    public function sizes(){
        return $this->belongsToMany(Size::class);
    }

    public function colors(){
        return $this->belongsToMany(Color::class);
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
