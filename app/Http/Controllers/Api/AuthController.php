<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Seller;
use Illuminate\Http\Request;
use function App\Helpers\translate;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\CreatePasswordRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected $userModel;
    public function __construct(User $user, Seller $seller)
    {
       $this->userModel = $user;
       $this->sellerModel = $seller;
    }

    public function checkPhone(Request $request){

        $validator = validator($request->all(), [
            'phone' => 'required|string',

        ]);
        $user = $this->userModel::where('phone',$request->phone)->first();

        if(is_null($user)){

          $user=  $this->userModel::create([
                'phone'=>$request->phone,
                'activation_code'=>  rand ( 1000 , 9999 ),
            ]);

            $data = [];
            $data['user_id'] = $user->id;
            return responseApi(200,
            'activation code sent to your number',$data);

        }else{
            $data=[];
            $data['user_id'] = $user->id;
            if(!is_null($user->password)){
                if($user->is_active == 1){
                    $data['is_active']= $user->is_active;
                    return responseApi(200,
                    'User registerd ',$data);
                }else{
                    $data['is_active']= $user->is_active;
                    return responseApi(200,
                    'User not registerd  ',$data);
                }
            }else{
                return responseApi(500,
                'create password to your account',$data);
            }
        }

    }

    public function checkCode(Request $request)
    {
         $validator = validator($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'code' => 'required|max:4',
        ]);

        if ($validator->fails())
            return responseApi('false', $validator->errors());

        $user = $this->userModel::where('id', $request->user_id)->first();
        $data = ['user_id'=>$user->id];

        if($user->activation_code == $request->code){
            return responseApi('200', 'activation code is correct', $data);
        }
        return responseApi('500', 'activation code is incorrect', $data);
    }
    
    public function save_password(CreatePasswordRequest $request){

        $user = $this->userModel::where('id', $request->user_id)->first();
        $data['user_id'] = $user->id;

        if(empty($user->password)){
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        return responseApi(200, 'Password saved successfuly', $data);
    }

    public function register(RegisterRequest $request){
        $user = $this->userModel::where('id', $request->user_id)->first();
        $data['user_id'] = $user->id;

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'country_id' => $request->country_id,
            'city_id' => $request->city_id,
            'area_id' => $request->area_id,
            'address' => $request->address,
            'lat' => $request->lat,
            'long' => $request->long,
            'image' => $request->image,
        ]);

        $this->sellerModel->create([
            'user_id' => $user->id,
            'store_name'=> $request->store_name,
            'wallet_number' => $request->wallet_number
        ]);

        return responseApi(200, 'User registered successfuly', $data);
    
    }

    public function login(Request $request)
    {
        $validator = validator($request->all(), [
            'phone' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails())
            return responseApi('false',
                $validator->errors());

        $user = User::where('phone',
            $request->phone)->first();

        if(!$user){
            return responseApi('false',
                'Not found user');
        }

        if (!$token=auth()->attempt($validator->validated())){
            $token = auth()->attempt(['phone'=>$request->phone,
                'password'=> $request->password]);
        }



        if (!$token){
            return responseApi('false',
                'Unauthorized');
        }
        return responseApi('success',
            translate('user login'),
            $this->createNewToken($token));
    }
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => new UserResource(auth()->user())
        ]);
    }
}
