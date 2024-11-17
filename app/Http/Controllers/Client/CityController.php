<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\BaseSearchRequest;
use App\Services\CityService;
use Illuminate\Http\Request;

class CityController extends Controller
{

    private CityService $cityService;

    /**
     * @param CityService $cityService
     */
    public function __construct(CityService $cityService)
    {
        $this->cityService = $cityService;
    }


    public function searchByPage(BaseSearchRequest $request)
    {
        $data = $this->cityService->searchByPage($request);
        return response()->json($data);
    }
}
