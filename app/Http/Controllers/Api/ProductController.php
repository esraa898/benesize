<?php

namespace App\Http\Controllers\Api;



use App\Models\Color;
use App\Models\Product;
use App\Models\ColorProduct;
use Illuminate\Http\Request;
use App\Models\FavouriteProduct;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductDetailResource;

class ProductController extends Controller
{
    protected $productModel;
    protected $colorProductModel;
    public function __construct(Product $product,ColorProduct $colorProduct)
    {
        $this->productModel = $product;
        $this->colorProductModel=$colorProduct;
    }


    public function productDetail($id)
    {
        $product = $this->colorProductModel::with('product')->where('product_id',$id)->first();
        $data= New ProductDetailResource($product);


            return responseApi(200, "product details Found", $data);
    }




    public function add_fav_product(Request $request){

        $validator = validator($request->all(), [
            'product_id' => 'required|integer',
            'color_id' => 'required|integer'
        ]);

        if ($validator->fails())
            return responseApi(403, $validator->errors()->first());

        $user = auth()->user();
        if(!$user){
            return responseApi(403, "user not found");
        }

        $fav_product = FavouriteProduct::create([
            'user_id' => $user->id,
            'product_id' => $request->product_id,
            'color_id' => $request->color_id
        ]);

        return responseApi(200, "Favourite product added", $fav_product);
    }

    public function remove_fav_product(Request $request){

        $validator = validator($request->all(), [
            'product_id' => 'required|integer',
            'color_id' => 'required|integer'
        ]);

        if ($validator->fails())
            return responseApi(403, $validator->errors()->first());

        $whereArray = array();
        $user = auth()->user();
        if(!$user){
            return responseApi(403, "user not found");
        }
        $whereArray = ['user_id'=> $user->id, 'product_id' => $request->product_id, 'color_id' => $request->color_id];
        $fav_product = FavouriteProduct::where($whereArray)->delete();

        return responseApi(200, "Favourite product deleted", $fav_product);
    }

    public function get_favourite_products(){

        $whereArray = array();
        $user = auth()->user();
        if(!$user){
            return responseApi(403, "user not found");
        }
        $whereArray = ['user_id'=> $user->id];
        $fav_products = FavouriteProduct::where($whereArray)->get();

        if($fav_products->isEmpty()){
            return responseApi(500, "Fav products empty");
        }
        return responseApi(200, "All favourite products", $fav_products);
    }

    public function addMedia(Request $request)
    {
        $validator = validator($request->all(), [
            'image' => 'required|Image|mimes:jpeg,jpg,png,gif',//|max:10000',
        ]);

        if ($validator->fails())return responseApi('false', $validator->errors()->first());

        $uploadedFile = $request->file('image');
        
        $this->productModel->addMedia($uploadedFile)
                ->withCustomProperties(['color_id' => $request->colorId,'product_id' => $request->productId])->toMediaCollection('images');

        return responseApi(200, 'image uploaded successfuly');
    }


}
