<?php

namespace App\Repositories\Service;

use App\Models\Service;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Facades\DB;

class ServiceRepository extends BaseRepository implements ServiceInterface
{

    public function model(): string
    {
        return Service::class;
    }

    public function remove($id)
    {
        $query = $this->model->find($id);
        $query->delete();
        return $query;
    }

    public function retore($id)
    {
        $query = $this->model->onlyTrashed()->where('id', $id)->first();
        $query->restore();
        return $query;

    }

    public function destroy($id)
    {
        $query = $this->model->withTrashed()->where('id', $id)->first();
        $query->forceDelete();
        return $query;
    }

    public function getByOrderId($orderId)
    {
        return Service::query()
            ->select('services.name', 'services.price', DB::raw('count(1) as service_quantity'), 'bs.status',
                DB::raw('count(1) * services.price as total_price'))
            ->join('booking_services as bs', 'bs.service_id', '=', 'services.id')
            ->where('bs.order_id', $orderId)
            ->groupBy('services.id', 'bs.status')->get()->toArray();
    }
}