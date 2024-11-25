<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('delete_api_logs', function () {
    $this->info('Deleting all api logs older than 30 days');
    DB::table('api_logs')->where('created_at', '<', now()->subDays(30))->delete();
})->purpose('Delete all api logs older than 30 days')->daily();

// invalidate cache every hour
Artisan::command('invalidate_cache', function () {
    $this->info('Invalidating cache');
    Cache::forget('external_api_data');
})->purpose('Invalidate cache')->hourly();
