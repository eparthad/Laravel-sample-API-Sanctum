<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ReviewController;
use Illuminate\Http\Request;
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

// Public Route

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

// Review
Route::post('/review/store', [ReviewController::class, 'store']);

Route::group(['middleware' => ['auth:sanctum']], function(){

    // Product 
    Route::get('/products/search/{name}', [ProductController::class, 'search']);
    Route::resource('products', ProductController::class);

    //Category
    Route::resource('categorys', CategoryController::class);

    //Tag
    Route::resource('tags', TagController::class)->except(['create','edit']);

    Route::post('/logout', [UserController::class, 'logout']);
});



// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
