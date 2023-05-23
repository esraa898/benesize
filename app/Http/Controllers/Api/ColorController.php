<?php

namespace App\Http\Controllers\Api;

use App\Models\Color;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ColorsResource;

class ColorController extends Controller
{
    public function index()
    {
        $colors = Color::all();
        $data= ColorsResource::collection($colors);
        return responseApi('200', "Colors Found", $data);
    }

    public function store(Request $request)
    {
        $validator = validator($request->all(), [
            "name" => "required|string",
            'rgb' => 'required|string',
        ]);

        if ($validator->fails())
            return responseApi('false', $validator->errors()->all());

        $color = new Color();

        $color->name = $request->input("name");
        $color->rgb = $request->input("rgb");

        $color->save();

        return responseApi("success", "Color Added", $color);
    }

    public function show(string $id)
    {
        $color = Color::find($id);

        if(!$color) {
            return responseApi("false", "Color Not Found", $color);
        }

        return responseApi("success", "Color Found", $color);
    }

    public function edit(Request $request, $id)
    {
        $color = Color::find($id);

        if (!$color) {
            return responseApi("failed", "Color Not Found", $color);
        }
        return responseApi("success", "Color Found", $color);
    }

    public function update(Request $request, $id)
    {
        $color = Color::find($id);
        $color->name = $request->input("name");
        $color->rgb = $request->input("rgb");
        $color->save();

        return responseApi("success", "Color Updated", $color);
    }

    public function destroy(Request $request, $id)
    {
        $color = Color::find($id);
        if (!$color) {
            return responseApi("failed", "Color Not Found", $color);
        }

        $color->delete();

        return responseApi("success", "Color Deleted");
    }
}
