<?php

namespace App\Http\Controllers\Api;

use App\Models\Size;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SizesResource;

class SizeController extends Controller
{
    public function index()
    {
        $sizes = Size::all();
        $data= SizesResource::collection($sizes);

        return responseApi(200, "Sizes Found", $data);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = validator($request->all(), [
            "name" => "required|string"
        ]);

        if ($validator->fails())
            return responseApi(403, $validator->errors()->first());

        $size = new Size();

        $size->name = $request->input("name");

        $size->save();

        return responseApi(200, "Size Added", $size);
    }


    public function show(Size $size)
    {
        //
    }

    public function edit(Request $request, $id)
    {
        $size = Size::find($id);

        if (!$size) {
            return responseApi(500, "Size Not Found", $size);
        }
        return responseApi(200, "Size Found", $size);
    }

    public function update(Request $request, $id)
    {
        $size = Size::find($id);
        $size->name = $request->input("name");
        $size->save();

        return responseApi(200, "Size Updated", $size);
    }

    public function destroy(Request $request, $id)
    {
        $size = Size::find($id);
        if (!$size) {
            return responseApi(500, "Size Not Found", $size);
        }

        $size->delete();

        return responseApi(200, "Size Deleted");
    }
}
