<?php

namespace App\Http\Controllers\Api;

use App\Models\Size;
use App\Models\Color;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with("category")
            ->with("colors")
            ->with("sizes")
            ->get();
        return responseApi('success', "Products Found", $products);
    }

    public function create() {
        $categories = Category::all();
        $colors = Color::all();
        $sizes = Size::all();

        return responseApi('success', "Data Found", compact("categories", "colors", "sizes"));
    }

    public function store(Request $request)
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
            return responseApi('false', $validator->errors()->all());

        $product = new Product();

        $product->name = $request->input("name");
        $product->description = $request->input("description");
        $product->min_price = $request->input("min_price");
        $product->max_price = $request->input("max_price");
        $product->category_id = $request->input("category_id");

        $product->save();

        $product->sizes()->sync($request->input('sizes'));
        $product->colors()->sync($request->input('colors'));

        return responseApi("success", "Product Added", $product);
    }

    public function show(string $id)
    {
        $product = Product::with("category")
            ->with("colors")
            ->with("sizes")
            ->find($id);

        if(!$product) {
            return responseApi("false", "Product Not Found", $product);
        }

        return responseApi("success", "Product Found", $product);
    }

    public function edit($id)
    {
        $product = Product::with("category")
            ->with("colors")
            ->with("sizes")
            ->find($id);

        $sizes = Size::all();
        $categories = Category::all();
        $colors = Color::all();

        if (!$product) {
            return responseApi("failed", "Product Not Found", $product);
        }
        return responseApi("success", "Product Found", compact("product", "sizes", "categories", "colors"));
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
            return responseApi('false', $validator->errors()->all());

        $product = Product::find($id);

        $product->name = $request->input("name");
        $product->description = $request->input("description");
        $product->min_price = $request->input("min_price");
        $product->max_price = $request->input("max_price");
        $product->category_id = $request->input("category_id");

        $product->sizes()->sync($request->input("sizes"));
        $product->colors()->sync($request->input("colors"));

        $product->save();

        return responseApi("success", "Product Updated", $product);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return responseApi("failed", "Product Not Found", $product);
        }

        $product->delete();

        return responseApi("success", "Product Deleted");
    }
}
