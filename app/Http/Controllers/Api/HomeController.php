<?php

namespace App\Http\Controllers\Api;

use App\Models\Size;
use App\Models\Color;
use App\Models\Slider;
use App\Models\Product;

use Illuminate\Http\Request;
use App\Http\Traits\ProductFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\SizesResource;
use App\Http\Resources\ColorsResource;
use App\Http\Resources\SliderResource;
use App\Http\Resources\ProductResource;
use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\ProductColorsResource;

class HomeController extends Controller
{
    use ProductFilter;
    protected $sliderModel;
    public function __construct(Slider $slider)
    {
        $this->sliderModel = $slider;
    }


    public function sliders(){

        $sliders = $this->sliderModel::get();
        $sliders_data = SliderResource::collection($sliders);
        return $sliders_data;
    }

    public function get_home_info(Request $request){
        $data = array();
        $products =  $this->products();
        $data['products'] = ProductColorsResource::collection($products);


        $slider_response = $this->sliders();
        $data ['slider'] = $slider_response;

        return responseApi(200, "Products Found", $data);
    }

    public function product_filters(){
        $colors = Color::get();
        $sizes  = Size::get();
        $min    = Product::select('price')->min('price');
        $max    = Product::select('price')->max('price');
        $data=[
           'sizes' => SizesResource::collection($sizes),
           'colors'=>  ColorsResource::collection($colors) ,
           'min_price'=>$min,
           'max_price'=>$max,

        ];
        return responseApi(200, "Products Found", $data);
    }

}
