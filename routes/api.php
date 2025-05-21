<?php

use App\Http\Controllers\{
    PaymentController,
    UserController,
    ProjectController,
    RewardsController,
    MercadoPagoWebHookController,
};
use Illuminate\Support\Facades\Route;

Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);
Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/users', [UserController::class, 'index']);
Route::get('/project/{id}', [ProjectController::class, 'findById']);

Route::post('/webhook/mercadopago', [MercadoPagoWebHookController::class, 'handle']);


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

    Route::post('/payreward', [PaymentController::class, 'createReference']);
    Route::get('/payment/{user}', [PaymentController::class, 'getPayments']);
});
