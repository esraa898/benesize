<?php

namespace App\Http\Controllers\Api;

use App\Models\Size;
use App\Models\Color;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\FavouriteProduct;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    protected $productModel;
    public function __construct(Product $product)
    {
        $this->productModel = $product;
    }

    public function create() {
        $categories = Category::all();
        $colors = Color::all();
        $sizes = Size::all();

        return responseApi(200, "Data Found", compact("categories", "colors", "sizes"));
    }

    public function store(ProductRequest $request)
    {

        $product = new Product();

        $product->name = $request->input("name");
        $product->description = $request->input("description");
        $product->min_price = $request->input("min_price");
        $product->max_price = $request->input("max_price");
        $product->max_price = $request->input("price");

        $product->max_price = $request->input("is_best_seller");
        $product->max_price = $request->input("is_new");
        $product->max_price = $request->input("is_on_sale");
        $product->max_price = $request->input("is_new_arrival");
        
        $product->category_id = $request->input("category_id");

        $product->save();

        $product->sizes()->sync($request->input('sizes'));
        $product->colors()->sync($request->input('colors'));

        return responseApi(200, "Product Added", $product);
    }

    public function show(string $id)
    {
        $product = $this->productModel::with("category")
            ->with("colors")
            ->with("sizes")
            ->find($id);

        if(!$product) {
            return responseApi(500, "Product Not Found", $product);
        }

        return responseApi(200, "Product Found", $product);
    }

    public function edit($id)
    {
        $product = $this->productModel::with("category")
            ->with("colors")
            ->with("sizes")
            ->find($id);

        $sizes = Size::all();
        $categories = Category::all();
        $colors = Color::all();

        if (!$product) {
            return responseApi(500, "Product Not Found", $product);
        }
        return responseApi(200, "Product Found", compact("product", "sizes", "categories", "colors"));
    }

    public function update(Request $request, $id)
    {
        $validator = validator($request->all(), [
            'name' => 'required|max:100',
            'description' => 'required|string',
            'min_price' => 'required|numeric|min:0',
            'max_price' => 'required|numeric|min:0|gt:min_price',
            'category_id' => 'required|exists:categories,id',
            'sizes' => 'array',
            'colors' => 'array',
        ]);

        if ($validator->fails())
            return responseApi(403, $validator->errors()->all());

        $product = Product::find($id);

        $product->name = $request->input("name");
        $product->description = $request->input("description");
        $product->min_price = $request->input("min_price");
        $product->max_price = $request->input("max_price");
        $product->category_id = $request->input("category_id");

        $product->sizes()->sync($request->input("sizes"));
        $product->colors()->sync($request->input("colors"));

        $product->save();

        return responseApi(200, "Product Updated", $product);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return responseApi(500, "Product Not Found", $product);
        }

        $product->delete();

        return responseApi(200, "Product Deleted");
    }

    public function add_fav_product(Request $request){

        $validator = validator($request->all(), [
            'product_id' => 'required|integer',
            'color_id' => 'required|integer'
        ]);

        if ($validator->fails())
            return responseApi(403, $validator->errors()->all());

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
            return responseApi(403, $validator->errors()->all());

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

    
}
