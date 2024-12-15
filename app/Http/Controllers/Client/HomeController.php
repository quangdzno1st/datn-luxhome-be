<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\BaseSearchRequest;
use App\Models\Banner;
use App\Repositories\City\CityRepository;
use App\Repositories\Hotel\HotelRepository;
use App\Repositories\User\UserRepository;
use App\Services\impl\UserServiceImpl;
use App\Services\OrderService;
use Carbon\Carbon;

class HomeController extends Controller
{

    private CityRepository $cityRepos;
    private HotelRepository $hotelRepos;
    private OrderService $orderService;

    public function __construct(
        CityRepository  $cityRepos,
        HotelRepository $hotelRepos,
        OrderService    $orderService,
        UserServiceImpl $userServices,
        UserRepository  $userRepository,
        HotelRepository $hotelRepository,

    )
    {
        $this->userServices = $userServices;
        $this->userRepository = $userRepository;
        $this->hotelRepository = $hotelRepository;
        $this->cityRepos = $cityRepos;
        $this->hotelRepos = $hotelRepos;
        $this->orderService = $orderService;
    }


    public function index()
    {
        $hotels = $this->hotelRepos->getAllForClient();
        $cities = $this->cityRepos->searchByPage(new BaseSearchRequest());
        $cityIds = array_map(function ($city) {
            return $city['id'] ?? null;
        }, $cities->toArray());
        $banners = Banner::query()->where('status', 1)->latest('created_at')->get();

        $totalOrderMap = $this->orderService->getTotalOrderMapByCityId($cityIds);
        return view("client/home", compact('cities', 'hotels', 'totalOrderMap', 'banners'));
    }

    public function contact()
    {
        return view("client.contact");

    }
}