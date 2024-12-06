<?php

namespace App\Services\impl;

use App\Constant\Enum\ActiveStatusEnum;
use App\Models\Service;
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

    public function searchByPage($idHotel, Request $request)
    {
        $query = $this->genSqlClauseSearchService($idHotel, $request);
        return $query->get()->toArray();
    }

    public function genSqlClauseSearchService($idHotel, Request $request)
    {
        $query = Service::query()
            ->where('status', ActiveStatusEnum::Active->value)
            ->where('hotel_id', $idHotel);

        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->whereHas('service', function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('description', 'like', "%" . $keyword . "%");
            });
        }

        if ($request->filled('min_price')) {
            $minPrice = $request->min_price;
            $query->whereHas('service', function ($query) use ($minPrice) {
                $query->where('price', '>=', $minPrice);
            });
        }

        if ($request->filled('max_price')) {
            $maxPrice = $request->max_price;
            $query->whereHas('service', function ($query) use ($maxPrice) {
                $query->where('price', '<=', $maxPrice);
            });
        }

        if ($request->filled('type')) {
            $type = $request->input('type');
            $query->where(function ($query) use ($type) {
                $query->where('type', $type);
            });
        }

        return $query->latest('created_at');
    }

    public function getServicesByIdHotel($idHotel, Request $request)
    {
        $query = $this->genSqlClauseSearchService($idHotel, $request);
        return $query->paginate(10);
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
        $model = $this->hotelServiceRepository->find($id);
        $hotelService = $this->hotelServiceRepository->delete($model);
        return $hotelService;
    }

    public function deleteMulti(Request $request)
    {
        $data = $request->input('services');
        return $this->hotelServiceRepository->deleteMany($data);
    }
}
