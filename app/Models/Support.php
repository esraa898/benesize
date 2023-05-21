<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'seller_name',
        'seller_phone',
        'subject',
        'description'
    ];
}
