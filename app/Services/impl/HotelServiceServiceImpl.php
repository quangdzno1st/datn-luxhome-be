<?php

namespace App\Services\impl;

use App\Repositories\HotelService\HotelServiceRepository;
use App\Services\HotelServiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HotelServiceServiceImpl implements HotelServiceService
{
    protected HotelServiceRepository $hotelServiceRepository;

    public function __construct(HotelServiceRepository $hotelServiceRepository)
    {
        $this->hotelServiceRepository = $hotelServiceRepository;
    }
    public function getServicesByIdHotel($idHotel)
    {
        $hotelServices = $this->hotelServiceRepository->getAll($idHotel);
        return $hotelServices;
    }
    public function create($idHotel, Request $request)
    {
        $data = $request->input('hotel_service');
        foreach ($data as &$item){
            $item['id'] = Str::uuid()->toString();
            $item['hotel_id'] = $idHotel;
            $item['created_at'] = date('Y-m-d H:i:s');
            $item['updated_at'] = date('Y-m-d H:i:s');
        }

        return $this->hotelServiceRepository->add($data);
    }
    public function delete($id)
    {
        $model =  $this->hotelServiceRepository->find($id);
        $hotelService = $this->hotelServiceRepository->delete($model);
        return $hotelService;
    }

    public function deleteMulti(Request $request)
    {
        $data = $request->input('ids');
        return $this->hotelServiceRepository->deleteMany($data);
    }
}