<?php

namespace App\Services\impl;

use App\Repositories\Hotel\HotelRepository;
use App\Services\HotelService;

class HotelServiceImpl implements HotelService
{
    private HotelRepository $hotelRepos;

    /**
     * @param HotelRepository $hotelRepos
     */
    public function __construct(HotelRepository $hotelRepos)
    {
        $this->hotelRepos = $hotelRepos;
    }


}