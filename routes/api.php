<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdoptionAnimalController;
use App\Http\Controllers\AdoptionApplicationController;
use App\Http\Controllers\ProductInNeedController;
use App\Http\Controllers\VolunteerApplicationController;
use App\Http\Controllers\StrayPostController;
use App\Http\Controllers\ArticleController;

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

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->post('/logout', [UserController::class, 'logout']);
Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);
Route::get('users/{id}', [UserController::class, 'getUser']);
Route::get('users', [UserController::class, 'index']);
/* Route::put('/updateProfile', [UserController::class, 'updateProfile'])->middleware('auth:sanctum'); */
Route::middleware('auth:sanctum')->put('/update-profile', [UserController::class, 'updateProfile']);
/*Forgot Password*/
Route::post('/forgot-password', [UserController::class, 'forgotPassword']);
Route::post('/reset-password', [UserController::class, 'resetPassword'])->name('password.reset');

/* Route::post('forgotpassword', [UserController::class, 'sendResetLinkEmail']);/* )->name('password.email') */ 
Route::get('/resetpassword/{token}', [UserController::class, 'showResetForm']);/* ->name('password.reset'); */

/* Route::post('/upload-image', [ImageController::class, 'upload']); */
Route::get('adoptionanimals', [AdoptionAnimalController::class, 'index']);
Route::post('adoptionanimals', [AdoptionAnimalController::class, 'store']);
Route::get('adoptionanimals/{id}', [AdoptionAnimalController::class, 'edit']);
Route::put('adoptionanimals/{id}', [AdoptionAnimalController::class, 'update']);
Route::delete('adoptionanimals/{id}', [AdoptionAnimalController::class, 'destroy']);
Route::get('/available-animals', [AdoptionAnimalController::class, 'getAvailableAnimals']);

Route::get('adoptionapplications', [AdoptionApplicationController::class, 'index']);
Route::post('/adoptionapplications', [AdoptionApplicationController::class, 'store']);
Route::delete('adoptionapplications/{id}', [AdoptionApplicationController::class, 'destroy']);
Route::put('approve-adoptionapplication/{id}', [AdoptionApplicationController::class, 'approveAdoptionApplication']);
Route::put('reject-adoptionapplication/{id}', [AdoptionApplicationController::class, 'rejectAdoptionApplication']);

/*product in need*/
Route::get('products', [ProductInNeedController::class, 'index']);
Route::post('products', [ProductInNeedController::class, 'store']);
Route::get('products/{id}', [ProductInNeedController::class, 'edit']);
Route::put('products/{id}', [ProductInNeedController::class, 'update']);
Route::delete('products/{id}', [ProductInNeedController::class, 'destroy']);

Route::get('volunteerapplications', [VolunteerApplicationController::class, 'index']);
Route::post('/volunteerapplications', [VolunteerApplicationController::class, 'store']);
Route::delete('/volunteerapplications/{id}', [VolunteerApplicationController::class, 'destroy']);
Route::put('approve-volunteerapplication/{id}', [VolunteerApplicationController::class, 'approveVolunteerApplication']);
Route::put('reject-volunteerapplication/{id}', [VolunteerApplicationController::class, 'rejectVolunteerApplication']);

/*Stray Post*/
Route::get('strayposts', [StrayPostController::class, 'index']);
Route::post('strayposts', [StrayPostController::class, 'store']);
Route::get('strayposts/{id}', [StrayPostController::class, 'show']);
Route::put('strayposts/{id}', [StrayPostController::class, 'update']);
Route::delete('strayposts/{id}', [StrayPostController::class, 'destroy']);

Route::get('articles', [ArticleController::class, 'index']);
Route::post('articles', [ArticleController::class, 'store']);
Route::get('articles/{id}', [ArticleController::class, 'show']);
Route::put('articles/{id}', [ArticleController::class, 'update']);
Route::delete('articles/{id}', [ArticleController::class, 'destroy']);
/* upload-ckeditor-image */




