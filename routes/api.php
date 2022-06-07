<?php

use App\Http\Controllers\AuthController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/signup_admin',[AuthController::class,'admin']);
Route::post('/signup_user',[AuthController::class,'user']);
Route::post('/signup_company',[AuthController::class,'company']);
Route::post('/login',[AuthController::class,'login']);
Route::middleware(['auth:admin_api'])->group(function() {
Route::post('/a',[AuthController::class,'ad']);});

