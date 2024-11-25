<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    private $apiKey;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.openweather.key');
        $this->baseUrl = config('services.openweather.base_url');
    }

    public function getWeatherData(string $location = 'Nigeria,ng')
    {
        $cacheKey = 'weather_data_' . md5($location);

        $data = Cache::remember($cacheKey, now()->addHour(), function () use ($location) {
            $response = Http::get($this->baseUrl, [
                'q' => $location,
                'appid' => $this->apiKey,
            ]);

            if (!$response->successful()) {
                // log error

                throw new \Exception('Failed to fetch weather data for location: ' . $location);
            }

            return $response->json();
        });

        return $data;
    }
}
