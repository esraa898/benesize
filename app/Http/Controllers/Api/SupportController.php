<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Support;

class SupportController extends Controller
{
    //
    protected $supportModel;

    public function __construct(Support $support)
    {
        $this->supportModel = $support;
    }

    public function store(Request $request){

        $this->supportModel::create([
            'seller_name' => $request->seller_name,
            'seller_phone' => $request->seller_phone,
            'subject' => $request->subject,
            'description' => $request->description
        ]);

        return responseApi(200, 'Message saved');
    }
}
