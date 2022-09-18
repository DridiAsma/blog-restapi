<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommantaireController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\PostController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function()
{
    //User Auth
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);


    // Post
    Route::get('/posts', [PostController::class, 'index']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::get('/posts/{id}', [PostController::class, 'show']);
    Route::put('/posts/{id}', [PostController::class, 'update']);
    Route::delete('/posts/{id}', [PostController::class, 'delete']);


    //commantaire
    Route::get('/posts/{id}/comments', [CommantaireController::class, 'index']); //all comments
    Route::post('/posts/{id}/comments', [CommantaireController::class, 'store']); // create comments
    Route::put('/comments/{id}', [CommantaireController::class, 'update']); // update
    Route::delete('/comments/{id}', [CommantaireController::class, 'delete']); // delete



    //like or dislike post
    Route::post('/posts/{id}/likes', [LikeController::class, 'likeOrUnlike']);

});
