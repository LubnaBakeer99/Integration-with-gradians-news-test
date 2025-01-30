<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use App\Models\{Category,Article};
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SyncGuardianNewsArticlesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:guardian-news-articles';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync latest news articles from Guardian API';


    /**
     * Execute the console command.
     */
    public function handle()
    {
      
        $client = new Client();
        
        // The Guardian News API endpoint
        $url = 'https://content.guardianapis.com/search';
        $date =date('Y-m-d');
       
        // Set up query parameters
        $params = [
            'q' => null,
            'from-date' =>$date,
            'api-key' => env('GUARDIAN_API_KEY'),
            'show-fields' => 'headline,n'
        ];
        try {
            $response = $client->request('GET', $url, ['query' => $params]);

            if ($response->getStatusCode() == 200)
            {
                $content = $response->getBody()->getContents();
                $data = json_decode($content, true);

                foreach ($data['response']['results'] as $articleData) {
                   
                    $this->saveArticle($articleData);
                    Log::info("Successfully fetched and saved news article : " .$articleData['webTitle']);
              
                }

                Log::info("Successfully fetched and saved news articles.");
                $this->info("Successfully fetched and saved news articles");
            } else {
                Log::error("Failed to fetch news. Status code: " . $response->getStatusCode());
            }
        } catch (\Exception $e) {
            Log::error("An error occurred while fetching news: " . $e->getMessage());
        }
    }



    function saveArticle($article)  
    {
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



    function handlePublicationDate($articleWebPublicationDate)  {
        $carbon = new Carbon($articleWebPublicationDate);
        return $carbon->format('Y-m-d H:i:s');
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


     ////
     private function saveArticle_old($articleData)
    {
        $article = Article::firstOrCreate([
            'url' => $articleData['webPublicationDate']
        ], [
            'title' => $articleData['webTitle'],
            'content' => $articleData['fields']['headline'],
             'publication_date' => $articleData['webPublicationDate']
        ]);
         $this->info($article);
        if (!$article->category_id) {
            $category = Category::firstOrCreate(['name' => $articleData['sectionName']]);
            $article->update(['category_id' => $category->id]);
        }

        // if (!$article->author_id) {
        //     $author = Author::firstOrCreate(['name' => $articleData['webTitle']]);
        //     $article->update(['author_id' => $author->id]);
        // }
    }
}
