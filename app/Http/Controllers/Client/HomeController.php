<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\BaseSearchRequest;
use App\Repositories\City\CityRepository;
use App\Repositories\Hotel\HotelRepository;
use App\Services\OrderService;

class HomeController extends Controller
{

    private CityRepository $cityRepos;
    private HotelRepository $hotelRepos;
    private OrderService $orderService;

    public function __construct(
        CityRepository  $cityRepos,
        HotelRepository $hotelRepos,
        OrderService    $orderService
    )
    {
        $this->cityRepos = $cityRepos;
        $this->hotelRepos = $hotelRepos;
        $this->orderService = $orderService;
    }

    public function index()
    {
        $hotels = $this->hotelRepos->getAllForAdmin();
        $cities = $this->cityRepos->searchByPage(new BaseSearchRequest());
        $cityIds = array_map(function ($city) {
            return $city['id'] ?? null;
        }, $cities->toArray());

        $totalOrderMap = $this->orderService->getTotalOrderMapByCityId($cityIds);
        return view("client/home", compact('cities', 'hotels', 'totalOrderMap'));
    }


    public function hotelDetail($hotelId)
    {

    }
}
