<?php
namespace App\Services;

use App\Models\News;
use App\Models\NewsCurrency;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class NewsService
{
    public function fetchAndSaveNews(){
        try {
            $response = Http::get(config('services.cryptopanic.url'));
            if ($response->successful()) {
                $newsData = $response->json();
                $savedNews = [];

                foreach ($newsData['results'] as $newsItem) {
                    DB::transaction(function () use ($newsItem, &$savedNews) {
                        $news = News::create([
                            'title' => $newsItem['title'],
                            'published_at' => Carbon::parse($newsItem['published_at'])->format('Y-m-d H:i:s'),
                        ]);

                        $currencies = [];
                        if (!empty($newsItem['currencies'])) {
                            foreach ($newsItem['currencies'] as $currency) {
                                NewsCurrency::create([
                                    'news_id' => $news->id,
                                    'currency_code' => $currency['code'] ?? '',
                                    'currency_title' => $currency['title'] ?? '',
                                    'currency_slug' => $currency['slug'] ?? '',
                                    'currency_url' => $currency['url'] ?? '',
                                ]);

                                $currencies[] = [
                                    'currency_code' => $currency['code'] ?? '',
                                    'currency_title' => $currency['title'] ?? '',
                                    'currency_slug' => $currency['slug'] ?? '',
                                    'currency_url' => $currency['url'] ?? '',
                                ];
                            }
                        } else {
                            $currencies[] = [
                                'currency_code' => '',
                                'currency_title' => 'No Currency',
                                'currency_slug' => '',
                                'currency_url' => '',
                            ];
                        }

                        $savedNews[] = [
                            'id' => $news->id,
                            'title' => $news->title,
                            'published_at' => $news->published_at,
                            'currencies' => $currencies,
                        ];
                    });
                }

                $lastSaved = Redis::get('newsDataLastSave');
                if (!$lastSaved || Carbon::parse($lastSaved)->diffInMinutes(now()) >= 60) {
                    Redis::set('newsData', json_encode($savedNews));
                    Redis::set('newsDataLastSave', now()->toDateTimeString());
                }
            }
        } catch (\Exception $exception) {
            Log::error('FetchApiDataJob sırasında hata oluştu.', [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ]);
        }
    }
}
