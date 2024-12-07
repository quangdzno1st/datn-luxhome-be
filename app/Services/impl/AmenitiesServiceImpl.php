<?php

namespace App\Services\impl;

use App\Models\Attribute;
use App\Models\Service;
use App\Repositories\Amenities\AmenitiesInterface;
use App\Repositories\Amenities\AmenitiesRepository;
use App\Services\ServiceService;
use Illuminate\Http\Request;

class AmenitiesServiceImpl
{
    protected AmenitiesRepository $amenitiesRepository;

    public function __construct(AmenitiesRepository $amenitiesRepository)
    {
        $this->amenitiesRepository = $amenitiesRepository;
    }

    public function getAll(Request $request)
    {
        $query = Attribute::query();

        if ($request->has('content')) {
            $query->where('content', 'like', '%' . $request->get('content') . '%');
        }

        return $query->paginate(10);
    }
    public function getById($id)
    {
        return $this->amenitiesRepository->first(['id' => $id]);
    }
    public function createNew($data)
    {
        return $this->amenitiesRepository->create($data);
    }
    public function update($data, $id)
    {
        $model = $this->amenitiesRepository->find($id);
        return $this->amenitiesRepository->edit($model, $data);
    }
    public function delete($id)
    {
        return $this->amenitiesRepository->remove($id);
    }
    public function restore($id)
    {
        return $this->amenitiesRepository->retore($id);
    }
    public function forceDelete($id)
    {
        return $this->amenitiesRepository->destroy($id);
    }
}
