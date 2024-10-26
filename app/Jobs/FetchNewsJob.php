<?php

namespace App\Jobs;

use App\Services\NewsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class FetchNewsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected $newsService;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->newsService = new NewsService();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("FetchNewsJob çalıştı ve haberler alındı.");
        $this->newsService->fetchAndSaveNews();
    }
}
