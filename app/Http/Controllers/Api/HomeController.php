<?php

namespace App\Http\Controllers\Api;

use App\Models\Slider;
use App\Models\Product;
use Illuminate\Http\Request;
use function App\Helpers\translate;
use App\Http\Controllers\Controller;
use App\Http\Resources\SliderResource;
use App\Http\Resources\ProductResource;

class HomeController extends Controller
{
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

    public function get_home_info(){
        $data = array();
        $products = Product::with("category")
            ->with("colors")
            ->with("sizes")
            ->get();
        $data['products'] = ProductResource::collection($products);

        $slider_response = $this->sliders();
        $data ['slider'] = $slider_response;

        return responseApi('200', "Products Found", $data);
    }

}
