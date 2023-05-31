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
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\EditProfileRequest;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    protected $userModel;
    protected $sellerModel;

    public function __construct(User $user, Seller $seller)
    {
        $this->userModel = $user;
        $this->sellerModel = $seller;
        $this->middleware('auth.guard:api', ['except' => ['login', 'register', "sellerRegister", 'forgotPassword', 'checkPhone','checkCode', 'customRemoveAccount','ActiveRemoveAccount','save_password']]);
    }

    public function checkPhone(Request $request){

        $validator = validator($request->all(), [
            'phone' => 'required|string',

        ]);
        if ($validator->fails())
            return responseApi(405, $validator->errors()->first());

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

        } else{
            $data = [];
            $data['user_id'] = $user->id;
            if(!is_null($user->password)){
                if($user->is_active == 1){
                    $data['is_active']= $user->is_active;
                    return responseApi(200,
                        'User registerd ',$data);
                } else{
                    $data['is_active']= $user->is_active;
                    return responseApi(200,
                        'User not registerd  ',$data);
                }
            } else{
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
            return responseApi(405, $validator->errors());

        $user = $this->userModel::where('id', $request->user_id)->first();
        $data = ['user_id'=>$user->id];

        if($user->activation_code == $request->code){
            return responseApi(200, 'activation code is correct', $data);
        }
        return responseApi(500, 'activation code is incorrect', $data);
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

    public function register(Request $request){
        $validator = validator($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'name' => 'required|string|max:200',
            'store_name' => 'required|string|max:200',
            'wallet_number' => 'required|string|max:20',
            'country_id' => 'required|integer|exists:countries,id',
            'city_id' => 'required|integer|exists:cities,id',
            'gender' => 'required|string|in:male,female',
            'date_of_birth' => 'required|date',
        ]);

        if ($validator->fails())
            return responseApi(405, $validator->errors());

        $user = $this->userModel::where('id', $request->user_id)->first();

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'country_id' => $request->country_id,
            'city_id' => $request->city_id,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
        ]);

        // $this->sellerModel->create([
        //     'user_id' => $user->id,
        //     'store_name'=> $request->store_name,
        //     'wallet_number' => $request->wallet_number
        // ]);
        if( $request->hasfile('image')){
            $uploadedFile = $request->file('image');
            $extension = $uploadedFile->getClientOriginalExtension();
            $user->addMedia($uploadedFile)
                ->usingFileName(time().'.'.$extension)
                ->toMediaCollection('images');
        }

        $data['user'] = new UserResource($user);
        return responseApi(200, 'User registered successfuly', $data);

    }

    public function login(Request $request)
    {
        $validator = validator($request->all(), [
            'phone' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails())
            return responseApi(405,
                $validator->errors());

        $user = User::where('phone',
            $request->phone)->first();

        if(!$user){
            return responseApi(500,
                'Not found user');
        }

        if (! $token = JWTAuth::attempt($validator->validated())){
            $token = JWTAuth::attempt(['phone'=>$request->phone,
                'password'=> $request->password]);
        }

        if (!$token){
            return responseApi(500,
                'Unauthorized');
        }
        return responseApi(200,
            translate('user login'),$this->createNewToken($token)
        );
    }
    protected function createNewToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => new UserResource(auth()->user())
        ];
    }

    public function changePassword(ChangePasswordRequest $request)
    {

        $user = $this->userModel::where('id', $request->user_id)->first();

        if (Hash::check($request->old_password,  $user->password)) {
            $user->update(['password' => $request->new_password]);

            return responseApi(200, 'Password changed successfuly');
        }
        return responseApi(500, 'User not found');
    }


    public function logout()
    {

        auth()->logout();
        return responseApi(200, translate('user logout'));
    }

    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    public function editProfile(EditProfileRequest $request)
    {

        $user = $this->userModel::where('id', $request->user_id)->first();
        if(! $user){
            return responseApi(405, 'user not found');
        }
        $data['user_id'] = $user->id;

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'country_id' => $request->country_id,
            'city_id' => $request->city_id,
            'area_id' => $request->area_id,
            'address' => $request->address,
            'lat' => $request->lat,
            'lang' => $request->lang,
            'image' => $request->image,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
        ]);

        return responseApi(200, __('api.user profile update'), auth()->user());
    }

    public function removeAccount(Request $request)
    {
        $user = $this->userModel::where('id', $request->user_id)->first();

        if (Hash::check($request->password,  $user->password))
        {
            auth()->user()->delete();
            auth()->logout();

            return responseApi(200, __('api.Account deleted'));
        }
        return responseApi(500, __('api.password is incorrect'));
    }

    public function userProfile()
    {
        return responseApi('success', translate('get_data_success'),  new UserResource(auth()->user()));
    }

    public function uploadImage(Request $request)
    {
        $validator = validator($request->all(), [
            'image' => 'required|Image|mimes:jpeg,jpg,png,gif',//|max:10000',
        ]);

        $user = auth()->user();

        if ($validator->fails())return responseApi('false', $validator->errors()->all());

        $image = $user->getFirstMedia('images');

        if($image){
            $image->delete();
        }

        $uploadedFile = $request->file('image');
        $extension = $uploadedFile->getClientOriginalExtension();
        $user->addMedia($uploadedFile)
            ->usingFileName(time().'.'.$extension)
            ->toMediaCollection('images');

        $user = $this->userModel::where('id', $request->user_id)->first();
        return responseApi('success', translate('user profile update'), new UserResource($user));
    }

}
