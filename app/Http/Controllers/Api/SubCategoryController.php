<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SubCategoryResource;

class SubCategoryController extends Controller
{
    public function get_all_sub_categories(Request $request){

        $validator = validator($request->all(), [
            "category_id" => 'required|exists:categories,id'
        ]);

        if ($validator->fails())
            return responseApi(403, $validator->errors()->first());

        $sub_categories = SubCategory::with('category')->where('category_id', $request->category_id)->get();
        $sub_categories_response = SubCategoryResource::collection($sub_categories) ;

        if($sub_categories_response->isEmpty()){
            return responseApi(500, "Sub Categories not found");
        }

        $data = $sub_categories_response;
        return responseApi(200, "Sub Categories returns successfully", $data);
    }

    public function get_all_products(Request $request){

        $validator = validator($request->all(), [
            "sub_category_id" => "required|exists:sub_categories,id"
        ]);

        if ($validator->fails())
            return responseApi(403, $validator->errors()->first());

        $products = Product::with('subCategory')->where('sub_category_id', $request->sub_category_id)->get();
        if($products->isEmpty()){
            return responseApi(500, 'Products not found');
        }

        $products_response = ProductResource::collection($products);
        return responseApi(200, 'Products  found', $products_response);
    }
    
}
