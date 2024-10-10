<?php

namespace App\Services\impl;

use App\Repositories\Hotel\HotelRepository;
use App\Services\HotelService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
            $data['slug'] = Str::slug($data['name']);

            return $this->hotelRepos->create($data);

        } catch (\Exception $e) {
            // Log::error('Error: ' .$e->getMessage()); // Log bug
            throw $e;
        }
    }

    public function updateHotel($data, $slug)
    {

        try {
            $hotel = $this->getNonNullBySlug($slug);

            $data['slug'] = Str::slug($data['name']);

            $hotel->update($data);

            return $hotel;
        } catch (\Excdeption $e) {
            Log::error('Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function deleteHotel($slug)
    {
        try {
            $hotel = $this->getNonNullBySlug($slug);

            $hotel->delete();

            return $hotel;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function restoreHotel($slug)
    {
        try {
            $hotel = $this->getNonNullBySlug($slug);

            $hotel->restore();

            return $hotel;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function forceDeleteHotel($slug)
    {
        try {
            $hotel = $this->getNonNullBySlug($slug);

            $hotel->forceDelete();

            return $hotel;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function getNonNullBySlug($slug)
    {
        $hotel = $this->hotelRepos->detailHotel($slug);

        if ($hotel === null) {
            throw new \Exception('Not found');
        }

        return $hotel;
    }
}