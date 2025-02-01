<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{AuthenticationController,  GuardianApiController ,FavoritesController ,ArticleController};
 


// Get latest articles with filter
Route::get('/articles', [ArticleController::class, 'filterArtilces']);

 


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('user')
->middleware(['auth:api' ])
 ->group(function () { Route::controller(AuthenticationController::class)->group(function () {
              Route::post('/register', 'register')->withoutMiddleware(['auth:api']);
              Route::post('/login', 'login')->withoutMiddleware(['auth:api']);


    });
});


Route::post('/user/favourite/mark/{modelType}/{id}', [FavoritesController::class, 'favoriteOrUnFavourite'])
    ->where('modelType', 'authors|categories')
    ->middleware(['auth:api']);

Route::get('/user/favourite-articles', [FavoritesController::class, 'userFavouritesArticles'])->middleware(['auth:api']);


    