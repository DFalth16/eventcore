<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ExternalApiService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ExternalDataController extends Controller
{
    protected $externalService;

    public function __construct(ExternalApiService $externalService)
    {
        $this->externalService = $externalService;
    }

    public function index(Request $request): JsonResponse
    {
        $city = $request->query('city', 'La Paz');
        $data = $this->externalService->getWeather($city);

        return response()->json([
            'status' => 'success',
            'city' => $city,
            'weather' => $data
        ], 200);
    }
}