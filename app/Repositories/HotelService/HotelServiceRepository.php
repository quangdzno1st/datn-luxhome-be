<?php

namespace App\Repositories\HotelService;

use App\Models\HotelService;
use App\Repositories\Base\BaseRepository;

class HotelServiceRepository extends BaseRepository implements HotelServiceInterface
{
    public function model(): string
    {
        return HotelService::class;
    }

    public function getAll($id)
    {
        $query = $this->model;
        $this->resetModel();
        return $query->join('hotels', 'hotel_service.hotel_id', '=', 'hotels.id')
            ->join('services', 'hotel_service.service_id', '=', 'services.id')
            ->select('hotel_service.*', 'hotels.name as hotel_name', 'services.name as service_name', 'services.price as service_price', 'services.description as service_description')
            ->where('hotel_id', $id)->paginate(10);
    }

    public function add($data)
    {
        $query = $this->model;
        $query->insert($data);
        $this->resetModel();
        return $query;
    }

    public function deleteMany(array $ids)
    {
        $query = $this->model;
        return $query->destroy($ids);
    }

    public function getByOrgIdAndIds($orgId, $ids)
    {
        return HotelService::query()
            ->join("services as s", "hotel_service.service_id", "=", "s.id")
            ->where("hotel_service.hotel_id", $orgId)
            ->whereIn("s.id", $ids)
            ->select("s.id", "s.price")
            ->get();
    }
}