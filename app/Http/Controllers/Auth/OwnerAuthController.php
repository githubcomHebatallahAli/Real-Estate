<?php

namespace App\Http\Controllers\Auth;

use App\Models\Owner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Auth\OwnerRegisterRequest;
use App\Http\Resources\Auth\OwnerRegisterResource;
use Carbon\Carbon;

class OwnerAuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $validator = Validator::make($request->all(), $request->rules());


        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = auth()->guard('owner')->attempt($validator->validated())) {
            return response()->json([
                'message' => 'Invalid data'
            ], 422);
        }

        $owner = auth()->guard('owner')->user();

        if (!$owner->is_verified) {
            return response()->json([
                'message' => 'Your account is not verified. Please verify your phone number.'
            ], 403); // كود 403 يمثل الوصول المرفوض
        }
        
        if ($owner->ip !== $request->ip()) {
            $owner->ip = $request->ip();   
            $owner->save();
        }

        $owner->update([
            'last_login_at' => Carbon::now()->timezone('Africa/Cairo')
        ]);

        return $this->createNewToken($token);
    }

 

    /**
     * Register an Admin.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // Register an Admin.
    public function register(OwnerRegisterRequest $request)
    {
        $validator = Validator::make($request->all(), $request->rules());

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $ownerData = array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)],
    ['ip' => $request->ip()]
        );

        $owner = owner::create($ownerData);

        // $owner->save();
        // $owner->notify(new EmailVerificationNotification());

        // return response()->json([
        //     'message' => 'Owner Registration successful',
        //     'owner' =>new OwnerRegisterResource($owner)
        // ]);

        try {
            $verificationController = new VerficationController();
            $otpResponse = $verificationController->sendOtp(
                new \App\Http\Requests\VerficationPhoNumRequest(['phoNum' => $user->phoNum])
            );
    
            return response()->json([
                'message' => 'Owner registration successful. Please verify your phone number.',
                'user' => new OwnerRegisterResource($owner),
                'otp_identifier' => $owner->phoNum,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Owner registration successful. However, OTP could not be sent. Please try resending it.',
                'user' => new OwnerRegisterResource($user),
                'error' => $e->getMessage(),
            ], 201);
        }
    }


    public function logout()
    {
        
        $owner = auth()->guard('owner')->user();
    
        if ($owner->last_login_at) {
            $sessionDuration = Carbon::parse($owner->last_login_at)->diffInSeconds(Carbon::now());
            
            $owner->update([
                'last_logout_at' => Carbon::now(),  
                'session_duration' => $sessionDuration 
            ]);
        }
        auth()->guard('owner')->logout();
    
        return response()->json([
            'message' => 'Owner successfully signed out',
            'last_logout_at' => Carbon::now()->toDateTimeString(),  
            'session_duration' => gmdate("H:i:s", $sessionDuration)  
        ]);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->guard('owner')->refresh());
    }

    /**
     * Get the authenticated Admin.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        return response()->json([
            "data" => auth()->guard('owner')->user()
        ]);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        $owner = auth()->guard('owner')->user();
        $owner->last_login_at = Carbon::parse($owner->last_login_at)
        ->timezone('Africa/Cairo')->format('Y-m-d H:i:s');
        $owner = Owner::find(auth()->guard('owner')->id());
        return response()->json([

            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('owner')->factory()->getTTL() * 60,
            // 'owner' => auth()->guard('owner')->user(),
            'owner' => $owner,
        ]);
    }
}
