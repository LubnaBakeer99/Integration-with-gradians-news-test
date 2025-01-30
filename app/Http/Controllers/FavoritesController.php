<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{Favorite ,Article };
use Illuminate\Support\Facades\Auth;
use App\Helpers\ResponseHelper;
use App\Http\Resources\ArticleResource;

class FavoritesController extends Controller
{
    public function favoriteOrUnFavourite($modelType, $id)
    {
        $user = Auth::user();
        $favorite = Favorite::firstOrNew([
            'user_id' => $user->id,
            'favorited_type' => $modelType,
            'favorited_id' => $id,
        ]);

        if (!$favorite->exists) {
            $favorite->save();

            return ResponseHelper::create($favorite, "Successfully favorited {$modelType}");
       
        } else {
            $favorite->delete();
            return ResponseHelper::successWithMessage(null,  "Unfavorited {$modelType}");
       
        }
    }

    public function userFavouritesArticles()
    {
        $user = Auth::user();
        $favoriteAuthors = $user->favoriteAuthors()->pluck('id')->toArray();
        $favoriteCategories = $user->favoriteCategories()->pluck('id')->toArray();

        $articles = Article::query()
            ->when(!empty($favoriteAuthors), function ($query) use ($favoriteAuthors) {
                $query->whereIn('author_id', $favoriteAuthors);
            })
            ->when(!empty($favoriteCategories), function ($query) use ($favoriteCategories) {
                $query->whereIn('category_id', $favoriteCategories);
            });

        return ArticleResource::collection($articles->latest()->paginate(10));
    }

    public function userFavourites()
    {
        $user = Auth::user();
       
        $favorites =$user -> favoriteCategories();
        return ResponseHelper::success($favorites);
    }

  

}