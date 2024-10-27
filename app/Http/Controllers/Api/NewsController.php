<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Pagination\LengthAwarePaginator;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $newsData = json_decode(Redis::get('newsData'));
        $uniqueCurrencies = collect($newsData)
            ->pluck('currencies')
            ->flatten(1)
            ->unique('currency_code')
            ->filter(fn($currency) => $currency->currency_code !== '')
            ->values()
            ->all();
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $currency = $request->input('currency');
        $filteredData = collect($newsData)->filter(function ($news) use ($startDate, $endDate, $currency) {
            $publishedAt = Carbon::parse($news->published_at);

            if ($startDate && $publishedAt->lt(Carbon::parse($startDate)->startOfDay())) {
                return false;
            }

            if ($endDate && $publishedAt->gt(Carbon::parse($endDate)->endOfDay())) {
                return false;
            }

            if ($currency && !collect($news->currencies)->contains('currency_code', $currency)) {
                return false;
            }

            return true;
        })->values()->toArray();
        $filteredData = $this->paginateArray($filteredData, 5, $request->page);

        return view('pages.news', [
            'uniqueCurrencies' => $uniqueCurrencies,
            'filteredData' => $filteredData,
            'newsData' => $newsData
        ]);
    }

    private function paginateArray(array $items, int $perPage, int $page = null)
    {
        $page = $page ?: (LengthAwarePaginator::resolveCurrentPage() ?: 1);
        $paginatedItems = array_slice($items, ($page - 1) * $perPage, $perPage);

        return new LengthAwarePaginator(
            $paginatedItems,
            count($items),
            $perPage,
            $page,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );
    }
}
