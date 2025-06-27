<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\OrderController;
use App\Http\Middleware\JwtMiddleware;
use App\Http\Middleware\CheckUserType;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('/users/register', [LoginController::class, 'RegisterUser']);
Route::post('/users/login', [LoginController::class, 'LoginUser']);

Route::middleware([JwtMiddleware::class])->group(function () {
    Route::get('/users/me', [LoginController::class, 'AuthUser']);
    Route::post('/users/exit', [LoginController::class, 'Userlogout']);
});

Route::middleware(['jwt.auth', 'user.type:admin,stockist'])->group(function () {
    Route::get('/items', [ItemsController::class, 'GetItems']);
    Route::post('/items/register', [ItemsController::class, 'RegisterItems']);
    Route::put('/items/changed', [ItemsController::class, 'UpdateItems']);
    Route::delete('/items/{name}', [ItemsController::class, "DeleteItems"]);
});

Route::middleware(['jwt.auth','user.type:user,admin'])->group(function(){
    Route::post('/orders',[OrderController::class,'RegisterOrder']);
});
