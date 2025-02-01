<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{Favorite ,Article };
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ResponseHelper;
use App\Http\Resources\ArticleResource;

class ArticleController extends Controller
{

    function filterArtilces(Request $request)  {
        
        $perPage =$request->input('per_page');
       
        $articles = Article::query()
            ->when($request->input('category_id'), fn ($q) => $q->where('category_id', $request->input('category_id')))
            ->when($request->input('author_id'), fn ($q) => $q->where('author_id', $request->input('author_id')))
            ->when($request->input('publication_date'), fn ($q) => $q->whereDate('publication_date',$request->input('publication_date')))
         
            ->orderBy('publication_date');
 
        return ArticleResource::collection($articles->latest()->paginate($perPage));

       
    }

}