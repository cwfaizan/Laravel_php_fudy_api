<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\RecipeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PinVerificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/check-account', [AuthController::class, 'checkAccount']);
Route::get('/verify-pin', [PinVerificationController::class, 'verifyPin']);
Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [PasswordController::class, 'forgotPassword']);
Route::post('/reset-password', [PasswordController::class, 'resetPassword']);

// Route::apiResource('categories', CategoryController::class);
// Route::apiResource('recipes', RecipeController::class);

// Route::post('/bunnycdn', [ImageController::class, 'bunnycdnStore']);



// Route::middleware(['auth:api'])->group(function () {
//     Route::get('/logout', [AuthController::class, 'invalidateToken']);
//     Route::get('/users/{id}', [UserController::class, 'show']);
//     Route::put('/users/{id}', [UserController::class, 'update']);
//     Route::put('/change-contact-no/{userId}', [UserController::class, 'changeContactNo']);
//     Route::post('/change-password', [PasswordController::class, 'changePassword']);

//     Route::put('/bunnycdn', [ImageController::class, 'bunnycdnUpdate']);
// });
