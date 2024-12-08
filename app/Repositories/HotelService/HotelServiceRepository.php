<?php

namespace App\Repositories\HotelService;

use App\Models\HotelService;
use App\Models\Service;
use App\Repositories\Base\BaseRepository;

class HotelServiceRepository extends BaseRepository implements HotelServiceInterface
{
    public function model(): string
    {
        return HotelService::class;
    }

    public function getAll($id, $type = null, $keyword = null)
    {
        $query = HotelService::query();
        $query->join('hotels', 'hotel_service.hotel_id', '=', 'hotels.id')
            ->join('services', 'hotel_service.service_id', '=', 'services.id')
            ->select('hotel_service.*', 'hotels.name as hotel_name', 'services.name as service_name', 'services.price as service_price', 'services.description as service_description', 'services.type as service_type')
            ->where('hotel_id', $id);
        if (isset($type)) {
            $query->where('type', $type);
        }
        if (isset($keyword)) {
            $query->where(function($query) use ($keyword) {
                $query->where('hotels.name', 'like', "%$keyword%")
                ->orWhere('services.name', 'like', "%$keyword%")
                ->orWhere('services.price', 'like', "%$keyword%")
                ->orWhere('services.description', 'like', "%$keyword%");
            });
        }
        return $query->paginate(10);
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
        return Service::query()
            ->join("hotels as h", "h.id", "=", "services.hotel_id")
            ->where("services.hotel_id", $orgId)
            ->whereIn("services.id", $ids)
            ->select("services.id", "services.price", "services.name")
            ->get();
    }
}