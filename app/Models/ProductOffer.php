<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOffer extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'discount_percentage',
        'discount_value',
        'status',
        'product_id'
    ];
}
