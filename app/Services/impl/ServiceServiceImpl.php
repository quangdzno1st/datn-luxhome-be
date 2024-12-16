<?php

namespace App\Services\impl;

use App\Models\Service;
use App\Repositories\Service\ServiceRepository;
use App\Services\ServiceService;
use Illuminate\Http\Request;

class ServiceServiceImpl implements ServiceService
{
    protected ServiceRepository $serviceRepository;

    public function __construct(ServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    public function getAll(Request $request, $org_id)
    {
        $query = Service::query()->with('hotel');

        if (!empty($org_id)) {
            $query->where('hotel_id', $org_id);
        }

        if ($request->filled('hotel')) {
            $hotel_id = $request->input('hotel');
            $query->where('hotel_id', $hotel_id);
        }

        if ($request->filled('name')) {
            $name = $request->input('name');
            $query->where('name', 'like', '%' . $name . '%');
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
    
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('type')) {
            $type = $request->input('type');
            $query->where('type', $type);
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            $query->where('status', $status);
        }

        $services = $query->latest('created_at')->paginate(10);

        return $services;
    }
    public function getById($id)
    {
        $service = $this->serviceRepository->first(['id' => $id]);
        return $service;
    }
    public function createNew($data)
    {
        $service = $this->serviceRepository->create($data);
        return $service;
    }
    public function update($data, $id)
    {
        $model = $this->serviceRepository->find($id);
        $service = $this->serviceRepository->edit($model, $data);
        return $service;
    }
    public function delete($id)
    {
        $service = $this->serviceRepository->remove($id);
        return $service;
    }
    public function restore($id)
    {
        $service = $this->serviceRepository->retore($id);
        return $service;
    }
    public function forceDelete($id)
    {
        $service = $this->serviceRepository->destroy($id);
        return $service;
    }
}
