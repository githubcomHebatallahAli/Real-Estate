<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Validator;
use App\Notifications\SuccessfulRegistration;
use App\Http\Requests\Auth\UserRegisterRequest;
use App\Http\Resources\Auth\UserRegisterResource;
use App\Http\Requests\Auth\VerficationPhoNumRequest;

class UserAuthController extends Controller
{

    public function login(LoginRequest $request)
    {
        $validator = Validator::make($request->all(), $request->rules());


        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = auth()->guard('api')->attempt($validator->validated())) {
            return response()->json([
                'message' => 'Invalid data'
            ], 422);
        }

        $user = auth()->guard('api')->user();

        // if (!$user->is_verified) {
        //     return response()->json([
        //         'message' => 'Your account is not verified. Please verify your phone number.'
        //     ], 403);
        // }

        if ($user->ip !== $request->ip()) {
            $user->ip = $request->ip();
            $user->save();
        }

        $user->update([
            'last_login_at' => Carbon::now()->timezone('Africa/Cairo')
        ]);

        return $this->createNewToken($token);
    }


    public function register(UserRegisterRequest $request)
    {
        $validator = Validator::make($request->all(), $request->rules());

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        // if ($request->hasFile('photo')) {
        //     $photoPath = $request->file('photo')->store(User::PHOTO_FOLDER);
        //     // dd($photoPath);
        // }

        $UserData = array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)],
            ['ip' => $request->ip()],
            ['userType' => $request->userType ?? 'User']
        );

        // if (isset($photoPath)) {
        //     $UserData['photo'] = $photoPath;
        // }

        $User = User::create($UserData);

        // if ($request->hasFile('media')) {

        //     $path = $request->file('media')->store('User', 'public');
        //     $User->media()->create(['path' => $path]);
        // }

        // $User->load('media');

        // $User->save();
        // $User->notify(new EmailVerificationNotification());
        try {
            $verificationController = new VerficationController();

            $request = new VerficationPhoNumRequest(['phoNum' => $User->phoNum]);

            $verificationController->sendOtp($request);

            return response()->json([
                'message' => 'User registration successful. Please verify your phone number.',
                'User' => new UserRegisterResource($User),
                'otp_identifier' => $User->phoNum,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'User registration successful. However, OTP could not be sent. Please try resending it.',
                'User' => new UserRegisterResource($User),
                'error' => $e->getMessage(),
            ], 201);
        }
        return response()->json([
            'message' => 'User Registration successful',
            'User' =>new UserRegisterResource($User)
        ]);
    }


    public function logout()
{
    $user = auth()->guard('api')->user();

    if ($user->last_login_at) {
        $sessionDuration = Carbon::parse($user->last_login_at)->diffInSeconds(Carbon::now());

        $user->update([
            'last_logout_at' => Carbon::now(),
            'session_duration' => $sessionDuration
        ]);
    }
    auth()->guard('api')->logout();

    return response()->json([
        'message' => 'User successfully signed out',
        'last_logout_at' => Carbon::now()->toDateTimeString(),
        'session_duration' => gmdate("H:i:s", $sessionDuration)
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
        $user = auth()->guard('api')->user();
        $user->last_login_at = Carbon::parse($user->last_login_at)
        ->timezone('Africa/Cairo')->format('Y-m-d H:i:s');
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('api')->factory()->getTTL() * 60,
            'user' => auth()->guard('api')->user(),
        ]);
    }
}
