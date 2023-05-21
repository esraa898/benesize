<?php

namespace App\Http\Controllers\Api;

use App\Models\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SliderResource;
use function App\Helpers\translate;

class HomeController extends Controller
{
    protected $sliderModel;
    public function __construct(Slider $slider)
    {
     $this->sliderModel= $slider;
    }


    public function sliders(){

        $sliders= $this->sliderModel::get();
        $data= SliderResource::collection($sliders);
        return responseApi(200,translate('return_data_success'), $data);
    }



}
