<?php

namespace App\Http\Controllers\Api;

use App\Models\Slider;
use App\Models\Product;
use App\Models\ColorProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductColorsResource;

class SliderController extends Controller
{
    public function get_products(Request $request){

        $validator = validator($request->all(), [
            "slider_id" => "required|exists:sliders,id"
        ]);

        if ($validator->fails())
            return responseApi(403, $validator->errors()->first());
        
        $slider = Slider::where('id', $request->slider_id)->first();
        
        if($slider->type == 'MultiProduct'){
            $products_list_ids = $slider->type_id;
            $products_id = json_decode($products_list_ids, true);

            foreach ($products_id as $product_id){
                $product = ColorProduct::where('product_id', $product_id)->first();
                $data [] = new ProductColorsResource($product);
                
            }

            return responseApi(200, 'products return successfuly', $data);

        }
        return responseApi(200, 'slider type is not multi product');
        
    }
}
