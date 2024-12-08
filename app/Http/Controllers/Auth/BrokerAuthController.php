<?php

namespace App\Http\Controllers\Auth;

use App\Models\Broker;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Auth\BrokerRegisterRequest;
use App\Http\Resources\Auth\BrokerRegisterResource;

class BrokerAuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $validator = Validator::make($request->all(), $request->rules());


        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = auth()->guard('broker')->attempt($validator->validated())) {
            return response()->json([
                'message' => 'Invalid data'
            ], 422);

        }
        $broker = auth()->guard('broker')->user();
        if ($broker->ip !== $request->ip()) {
            $broker->ip = $request->ip(); 
            $broker->save();
        }

        return $this->createNewToken($token);
    }

    /**
     * Register an Admin.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // Register an Admin.
    public function register(BrokerRegisterRequest $request)
    {
        $validator = Validator::make($request->all(), $request->rules());

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $brokerData = array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)],
            ['ip' => $request->ip()]
        );

        $broker = Broker::create($brokerData);

        // $broker->save();
        // $broker->notify(new EmailVerificationNotification());

        return response()->json([
            'message' => 'Broker Registration successful',
            'broker' =>new BrokerRegisterResource($broker)
        ]);
    }




    /**
     * Log the admin out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->guard('broker')->logout();
        return response()->json([
            'message' => 'Broker successfully signed out'
        ]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->guard('broker')->refresh());
    }

    /**
     * Get the authenticated Admin.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        return response()->json([
            "data" => auth()->guard('broker')->user()
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
        $broker = Broker::find(auth()->guard('broker')->id());
        return response()->json([

            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('broker')->factory()->getTTL() * 60,
            // 'broker' => auth()->guard('broker')->user(),
            'broker' => $broker,
        ]);
    }
}
