<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class GuardianApiService
{
    public function getLatestArticles(): array
    {
        $url = 'https://content.guardianapis.com/search';
        $response = Http::get($url, [
            'api-key' => env('Guardian_API_KEY'),
            'show-fields' => 'all',
            'section' => 'world',
            'news' => true,
            'from-date' => Carbon::now()->subDays(7)->format('YYYY-MM-DD'),
            'to-date' => Carbon::now()->format('YYYY-MM-DD')
        ]);

         dd($response);
        return $response->json();
    }
}