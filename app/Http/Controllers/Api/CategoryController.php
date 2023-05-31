<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $data = CategoryResource::collection($categories);
        return responseApi('200', "Categories Found", $data);
    }

    public function store(Request $request)
    {
        $validator = validator($request->all(), [
            "name" => "required|string"
        ]);

        if ($validator->fails())
            return responseApi('false', $validator->errors()->all());

        $category = new Category();

        $category->name = $request->input("name");

        $category->save();

        return responseApi("success", "Category Added", $category);
    }

    public function show(string $id)
    {
        $category = Category::find($id);

        if(!$category) {
            return responseApi("false", "Category Not Found", $category);
        }

        return responseApi("success", "Category Found", $category);
    }

    public function edit(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return responseApi("failed", "Category Not Found", $category);
        }
        return responseApi("success", "Category Found", $category);
    }

    public function update(Request $request, $id)
    {
//        dd($request->all());
        $category = Category::find($id);
        $category->name = $request->input("name");
        $category->save();

        return responseApi("success", "Category Updated", $category);
    }

    public function destroy(Request $request, $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return responseApi("failed", "Category Not Found", $category);
        }

        $category->delete();

        return responseApi("success", "Category Deleted");
    }
}
