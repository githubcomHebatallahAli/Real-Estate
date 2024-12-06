<?php

namespace App\Http\Controllers\Auth;

use App\Models\Owner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Auth\OwnerRegisterRequest;
use App\Http\Resources\Auth\OwnerRegisterResource;

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
        // $owner = auth()->guard('owner')->user();

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
        // if (!Gate::allows('create', owner::class)) {
        //     return response()->json(['message' => 'Unauthorized'], 403);
        // }

        $validator = Validator::make($request->all(), $request->rules());

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $ownerData = array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        );

        $owner = owner::create($ownerData);

        // $owner->save();
        // $admin->notify(new EmailVerificationNotification());

        return response()->json([
            'message' => 'Owner Registration successful',
            'owner' =>new OwnerRegisterResource($owner)
        ]);
    }




    /**
     * Log the admin out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        // if (!Gate::allows('logout', owner::class)) {
        //     return response()->json(['message' => 'Unauthorized'], 403);
        // }
        auth()->guard('owner')->logout();
        return response()->json([
            'message' => 'Owner successfully signed out'
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
