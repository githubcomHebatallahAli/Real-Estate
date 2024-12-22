<?php


use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\Auth\UserRegisterResource;

Route::get('/test-user', function () {
    $user = User::find(7); // ابحث عن المستخدم بالـ ID المطلوب
    return new UserRegisterResource($user); // أعد البيانات في شكل JSON
});
