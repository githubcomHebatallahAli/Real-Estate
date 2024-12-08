<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Auth\UserRegisterRequest;
use App\Http\Resources\Auth\UserRegisterResource;

class UserAuthController extends Controller
{

    public function login(LoginRequest $request){
    	$validator = Validator::make($request->all(),$request->rules()

        );
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (! $token = auth()->guard('api')->attempt($validator->validated())) {
            return response()->json([
                'message' => 'Invalid Data'
            ], 422);
        }

        $user = auth()->guard('api')->user();
        if ($user->ip !== $request->ip()) {
            $user->ip = $request->ip();
            $user->save();
        }
        return $this->createNewToken($token);
    }



    public function register(UserRegisterRequest $request) {
        $validator = Validator::make($request->all(), $request->rules());

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $userData = array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)],
            ['ip' => $request->ip()]
        );



        $user = User::create($userData);
        return response()->json([
            'message' => 'user Registration successful',
            'user' => new UserRegisterResource($user)
        ], 201);
    }


    public function logout() {
        auth()->guard('api')->logout();
        return response()->json([
            'message' => 'user successfully signed out'
        ]);
    }

    public function refresh() {
        return $this->createNewToken([
            "data"=>auth()->guard('api')->refresh()
    ]);
    }

    public function userProfile() {
        return response()->json([
            "data"=>auth()->guard('api')->user()
    ]);
    }

    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('api')->factory()->getTTL() * 60,
            'user' => auth()->guard('api')->user(),

        ]);
    }
}
