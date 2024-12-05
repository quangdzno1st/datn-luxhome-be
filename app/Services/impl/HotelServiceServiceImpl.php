<?php

namespace App\Services\impl;

use App\Models\HotelService;
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

    public function getServicesByIdHotel($idHotel, Request $request)
    {
        $query = HotelService::query()->with('service');

        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->whereHas('service', function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('description', 'like', "%" . $keyword . "%");
            });
        }

        if ($request->filled('min_price')) {
            $minPrice = $request->min_price;
            $query->whereHas('service', function($query) use ($minPrice) {
                $query->where('price', '>=', $minPrice);
            });
        }
    
        if ($request->filled('max_price')) {
            $maxPrice = $request->max_price;
            $query->whereHas('service', function($query) use ($maxPrice) {
                $query->where('price', '<=', $maxPrice);
            });
        }

        if ($request->filled('type')) {
            $type = $request->input('type');
            $query->whereHas('service', function($query) use ($type) {
                $query->where('type', $type);
            });
        }
        $hotelServices = $query->latest('created_at')->paginate(10);
        return $hotelServices;
    }

    public function create($idHotel, Request $request)
    {
        $data = $request->input('services');

        foreach ($data as &$item) {
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
        $data = $request->input('services');
        return $this->hotelServiceRepository->deleteMany($data);
    }
}
