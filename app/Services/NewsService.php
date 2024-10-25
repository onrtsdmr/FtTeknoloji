<?php
namespace App\Services;

use App\Models\News;
use App\Models\NewsCurrency;
use Illuminate\Support\Facades\DB;

class NewsService
{
    public function saveNewsData($data) {
        DB::transaction(function() use ($data) {
            $news = News::create([
                'title' => $data['title'],
                'published_at' => $data['published_at'],
            ]);

            foreach ($data['currencies'] as $currency) {
                NewsCurrency::create([
                    'news_id' => $news->id,
                    'currency_code' => $currency['code'],
                    'currency_title' => $currency['title'],
                    'currency_slug' => $currency['slug'],
                    'currency_url' => $currency['url'],
                ]);
            }
        });
    }
}
