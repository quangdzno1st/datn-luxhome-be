<?php

namespace App\Services;

interface HotelService
{
    public function createNewHotel($data);
    public function updateHotel($data, $slug);

    public function deleteHotel($slug);
    public function restoreHotel($slug);
    public function forceDeleteHotel($slug);
}