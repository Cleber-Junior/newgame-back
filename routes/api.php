<?php

use App\Http\Controllers\{
    UserController,
    ProjectController
};
use Illuminate\Support\Facades\Route;

Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);
Route::get('/projects', [ProjectController::class, 'index']);

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::post('/createProject', [ProjectController::class, 'store']);
});
