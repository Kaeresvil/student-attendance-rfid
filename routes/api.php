<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\RoleController;
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


Route::post('login', [UserController::class, 'login']);
Route::post('logout', [UserController::class, 'logout'])->middleware('auth:sanctum');

Route::group(['prefix' => 'page','middleware' => 'auth:sanctum'], function() {
    Route::get('posts', [PostController::class,'index']);
    Route::post('add', [PostController::class,'add']);
    Route::post('update/{id}', [PostController::class,'update']);
    Route::get('edit/{id}', [PostController::class,'edit']);
    Route::delete('delete/{id}', [PostController::class,'delete']);


    Route::post('register', [UserController::class, 'register']);
    Route::get('users', [UserController::class,'Uindex']);
    Route::get('usersedit/{id}', [UserController::class,'Uedit']);
    Route::delete('usersdelete/{id}', [UserController::class,'Udelete']);
    Route::post('users/update/{id}', [UserController::class,'Uupdate']);



    Route::get('roles', [RoleController::class,'Rindex']);
    Route::post('role/add', [RoleController::class,'Radd']);
    Route::post('roleupdate/{id}', [RoleController::class,'Rupdate']);
    Route::get('roleedit/{id}', [RoleController::class,'Redit']);
    Route::delete('roledelete/{id}', [RoleController::class,'Rdelete']);
});
