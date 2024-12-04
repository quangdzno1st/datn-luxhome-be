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

    public function getAll(Request $request)
    {
        $query = Service::query();

        if ($request->has('keyword')) {
            $keyword = $request->input('keyword');
            $query->where(function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('description', 'like', "%" . $keyword . "%");
            });
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

        $services = $query->orderBy('created_at')->paginate(10);

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
