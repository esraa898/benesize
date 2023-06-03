<?php


namespace App\Http\Traits;

use App\Models\ColorProduct;
use App\Models\Product;

trait ProductFilter
{
    protected  function products()
    {
        $query = ColorProduct::with('color','product');


         $query->when(request('search'), function ($q, $search) {
            return $q->whereHas('product',function($searchname) use($search){
                return $searchname->where('name', 'LIKE', '%'. $search .'%');
            });
        });

        $query->when(request('is_new'), function ($q, $is_new) {
            return $q->whereHas('product',function($q) use($is_new){
                return $q->where('is_new',$is_new);
            });
        });

        $query->when(request('is_new_arrival'), function ($q, $is_new_arrival) {
            return $q->whereHas('product',function($q) use($is_new_arrival){
                return $q->where('is_new_arrival',$is_new_arrival);
            });
        });

        $query->when(request('is_new_arrival'), function ($q, $is_new_arrival) {
            return $q->whereHas('product',function($q) use($is_new_arrival){
                return $q->where('is_new_arrival',$is_new_arrival);
            });
        });

        $query->when(request('is_on_sale'), function ($q, $is_on_sale) {
            return $q->whereHas('product',function($q) use($is_on_sale){
                return $q->where('is_on_sale',$is_on_sale);
            });
        });


        $query->when(request('color'), function ($q, $color) {
            return $q->whereHas('color',function($colorname) use($color){
             return $colorname->where('hexa',$color);
            });
        });

        $query->when(request('max_price'), function ($q, $max_price) {
                return $q->where('price','<=',$max_price);

        });

        $query->when(request('min_price'), function ($q, $min_price) {
            return $q->where('price','>=',$min_price);

    });


        $query->when(request('size'), function ($q, $size) {
            return $q->whereHas('productColorSizes.size',function($sizename) use($size){
             return $sizename->where('name',$size);
            });
        });

        $query->when(request('offers'), function ($q) {
            return $q->whereHas('product.productOffer',function($offername) {
                return $offername->where('status',1);
               });

        });


        return $query->get();
    }
}
