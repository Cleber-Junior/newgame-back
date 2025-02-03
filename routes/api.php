<?php

use Illuminate\Http\Request;
use App\Http\Controllers\{
    PaymentController,
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
Route::get('/project/{id}', [ProjectController::class, 'findById']);
// Route::post('/payReward', [PaymentController::class, 'createReference']);
// Route::get('/getPayment', [PaymentController::class, 'getReference']);

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::post('/createProject', [ProjectController::class, 'store']);
    Route::get('/projects/{id}', [ProjectController::class, 'show']);
    Route::patch('/updateProject/{id}', [ProjectController::class, 'update']);
    Route::get('/project/image/{id}', [ProjectController::class, 'generateLinkImage']);
    Route::post('/finishProject/{id}', [ProjectController::class, 'finishProject']);

    Route::apiResource('/rewards', RewardsController::class);
    Route::get('/rewards/project/{id}', [RewardsController::class, 'allByProject']);

    Route::post('/editUser/{id}', [UserController::class, 'update']);
    Route::get('/user/projects/{id}', [ProjectController::class, 'allByUser']);
    Route::get('/user/image/{id}', [UserController::class, 'generateLinkImage']);

});
