<?php

use Illuminate\Http\Request;
use App\Http\Controllers\{
    UserController,
    ProjectController,
    RewardsController,
};
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);
Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/users', [UserController::class, 'index']);

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::post('/createProject', [ProjectController::class, 'store']);
    Route::get('/projects/{id}', [ProjectController::class, 'show']);
    Route::patch('/updateProject/{id}', [ProjectController::class, 'update']);
    Route::get('/user/projects/{id}', [ProjectController::class, 'allByUser']);
    Route::apiResource('/rewards', RewardsController::class);
    Route::get('/rewards/project/{id}', [RewardsController::class, 'allByProject']);
    Route::post('/finishProject/{id}', [ProjectController::class, 'finishProject']);
});
