<?php

namespace App\Services;

use App\Http\Requests\HotelSearchRequest;

interface HotelService
{
    public function createNewHotel($data);

    public function updateHotel($data, $slug);

    public function deleteHotel($slug);

    public function restoreHotel($slug);

    public function forceDeleteHotel($slug);
}