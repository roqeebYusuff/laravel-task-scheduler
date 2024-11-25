<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Services\WeatherService;

class ApiController extends BaseController
{
    private $weatherService;
    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function getCurrentWeatherData(Request $request)
    {
        try {
            $location = $request->query('location', 'Nigeria,ng');
            $data = $this->weatherService->getWeatherData($location);

            return $this->sendResponse(true, 200, 'Current weather data retrieved', $data);
        } catch (\Exception $e) {
            return $this->sendResponse(false, 500, $e->getMessage());
        }
    }
}
