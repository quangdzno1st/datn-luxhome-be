<?php

namespace App\Services;

use Illuminate\Http\Request;

interface HotelServiceService
{
    public function getServicesByIdHotel($idHotel);
    public function create($idHotel, Request $request);
    public function delete($id);
}