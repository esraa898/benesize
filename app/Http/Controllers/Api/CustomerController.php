<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;

class CustomerController extends Controller
{
    public function __construct(Customer $customer){
        $this->customerModel = $customer;
        $this->middleware('auth.guard:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_customer(Request $request)
    {
        $validator = validator($request->all(), [
            "name" => "required|string",
            "phone_number" => "required|string",
            "second_phone_number" => "string",
            "address" => "required|string",
            "area_id" => "required|exists:areas,id",
            "city_id" => "required|exists:cities,id"
        ]);

        if ($validator->fails())
            return responseApi(403, $validator->errors()->first());
       
        $this->customerModel->create([
            'phone_number' => $request->phone_number,
            'second_phone_number' => $request->phone_number,
            'name' => $request->name,
            'address' => $request->address,
            'area_id' => $request->area_id,
            'city_id' => $request->city_id,
            'user_id' => $request->user_id
        ]);
        
        return responseApi(200, 'customer added');
            
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_all_customers_per_user(Request $request)
    {
        
        $validator = validator($request->all(), [
            "user_id" => "required|exists:users,id"
        ]);

        if ($validator->fails())
            return responseApi(403, $validator->errors()->first());

        $customers = Customer::with('user')->where('user_id', $request->user_id)->get();
        if($customers->isEmpty()){
            return responseApi(500, 'customers not found');
        }

        $customers_response = CustomerResource::collection($customers);
        return responseApi(200, 'customers  found', $customers_response);
    }

    public function search_customer(Request $request){
       
        $search_params = $request->only('name', 'phone_number');

        if(isset($search_params['phone_number']) && $search_params['phone_number']){
            $q = $search_params['phone_number'];
            $Customer = Customer::where("phone_number", "LIKE", "%$q%")->where('user_id', auth()->user()->id)->get();
        
        }
        if(isset($search_params['name']) && $search_params['name']){
            $q = $search_params['name'];
            $Customer = Customer::where("name", "LIKE", "%$q%")->where('user_id', auth()->user()->id)->get();
        
        }

        if($Customer->isEmpty()){
            return responseApi(500,'customers not found', $Customer);
        }
        return responseApi(200,'customers found', $Customer);
      
    }
}
