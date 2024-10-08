<?php

namespace App\Http\Controllers;

use App\Repositories\Hotel\HotelRepository;
use App\Services\HotelService;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    private HotelService $hotelService;


    /**
     * @param HotelService $hotelService
     */
    public function __construct(
        HotelService $hotelService
    )
    {
        $this->$hotelService = $hotelService;
    }
}
