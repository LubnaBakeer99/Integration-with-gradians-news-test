<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\{Category,Article ,Author};
use Carbon\Carbon;

class GuardianApiController extends Controller
{
    public function search(Request $request)
    {
        $client = new Client();
        
        // The Guardian News API endpoint
        $url = 'https://content.guardianapis.com/search';
        $date =date('Y-m-d');
       
        // Set up query parameters
        $params = [
            'q' => 'football',
            'from-date' =>$date,
            'api-key' => env('GUARDIAN_API_KEY'),
            'show-fields' => 'headline,n'
        ];
         
       
        try {
            $response = $client->request('GET', $url, ['query' => $params]);
           
            if ($response->getStatusCode() == 200) {
                
                 
                $articles = json_decode($response->getBody(), true)['response']['results'];
               
                foreach ($articles as $article) {
                     
                    $this->storeArticle($article );
                }
                return response()->json($data['response']['results'], 200);
            } else {
                return response()->json(['error' => 'Failed to fetch news'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred'], 500);
        }

        
    }

    function storeArticle($article)  {
        $categoryName =$article['sectionName'];
        $categoryId =$this->storeCategory($categoryName);
        
       $articlePublishedDate =$this->handlePublicationDate($article['webPublicationDate']);
        $articleData = [
            'title'   => $article['webTitle'],
            'content' =>$article['fields']['headline'] ,
            'category_id' => $categoryId,
            'publication_date' => $articlePublishedDate,
            'url' => $article['webUrl'],
        ];
         
        $articleRecord =Article::firstOrCreate(
            [
                'url'=>$article['webUrl']
            ],
            $articleData
        );
  
 
    }

    
    function storeCategory($categoryName)  {
       $categoryData= [
        'name'=>$categoryName
       ];
      
       $category = Category::firstOrCreate(
        $categoryData,
        $categoryData
       );
       
       return $category->id; 
    }

    function handlePublicationDate($articleWebPublicationDate)  {
        $carbon = new Carbon($articleWebPublicationDate);
        return $carbon->format('Y-m-d H:i:s');
    }
}
