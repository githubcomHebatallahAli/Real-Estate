<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChaletController;



Route::controller(ChaletController::class)->group(
    function () {

   Route::get('/showAll/chalet','showAll');
   Route::post('/create/chalet', 'create');
   Route::get('/edit/chalet/{id}','edit');
   Route::post('/update/chalet/{id}', 'update');
   Route::delete('/delete/chalet/{id}', 'destroy');
   Route::get('/showDeleted/chalet', 'showDeleted');
Route::get('/restore/chalet/{id}','restore');
Route::delete('/forceDelete/chalet/{id}','forceDelete');
   });