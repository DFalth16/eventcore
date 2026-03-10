<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ExternalApiService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.weather_api.url');
        $this->apiKey = config('services.weather_api.key');
    }

    public function getWeather(string $city)
    {
        $response = Http::get($this->baseUrl, [
            'q' => $city,
            'appid' => $this->apiKey,
            'units' => 'metric'
        ]);

        return $response->successful() ? $response->json() : null;
    }
}