<?php

namespace App\Services\impl;

use App\Repositories\Hotel\HotelRepository;
use App\Services\HotelService;

class HotelServiceImpl implements HotelService
{
    private HotelRepository $hotelRepos;

    public function __construct(HotelRepository $hotelRepos)
    {
        $this->hotelRepos = $hotelRepos;
    }

    public function createNewHotel($data)
    {
        try {


            return $this->hotelRepos->create($data);

        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function updateHotel($data, $id)
    {

        try {
            $hotel = $this->getNonNullByID($id);

            $hotel->update($data);

            return $hotel;
        } catch (\Excdeption $e) {
            throw $e;
        }
    }

    public function deleteHotel($id)
    {
        try {
            $hotel = $this->getNonNullByID($id);

            $hotel->delete();

            return $hotel;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function restoreHotel($id)
    {
        try {
            $hotel = $this->getNonNullByID($id);

            $hotel->restore();

            return $hotel;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function forceDeleteHotel($id)
    {
        try {
            $hotel = $this->getNonNullByID($id);

            $hotel->forceDelete();

            return $hotel;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function getNonNullByID($id)
    {
        $hotel = $this->hotelRepos->detailHotel($id);

        if ($hotel === null) {
            throw new \Exception('Khách sạn không tồn tại hoặc đã bị xóa');
        }

        return $hotel;
    }
}