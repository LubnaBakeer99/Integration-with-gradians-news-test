<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{AuthenticationController,  GuardianApiController ,FavoritesController};
 


// Get latest articles
Route::get('/latest', [GuardianApiController::class, 'latestArticles']);

// Search articles
Route::post('/search', [GuardianApiController::class, 'search']);


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('user')
->middleware(['auth:api' ])
 ->group(function () { Route::controller(AuthenticationController::class)->group(function () {
              Route::get('/register', 'register')->withoutMiddleware(['auth:api']);
              Route::get('/login', 'login')->withoutMiddleware(['auth:api']);


    });
});


Route::post('/favourite/mark/{modelType}/{id}', [FavoritesController::class, 'favoriteOrUnFavourite'])
    ->where('modelType', 'authors|categories')
    ->middleware(['auth:api']);

    Route::post('/user/favourite-articles', [FavoritesController::class, 'userFavouritesArticles'])->middleware(['auth:api']);


    