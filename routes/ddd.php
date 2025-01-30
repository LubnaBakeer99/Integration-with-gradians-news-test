<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuardianApiController;

// Get latest articles
Route::get('/latest', [GuardianApiController::class, 'latestArticles']);

// Search articles
Route::post('/search', [GuardianApiController::class, 'search']);

