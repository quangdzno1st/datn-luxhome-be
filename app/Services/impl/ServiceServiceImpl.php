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
            $query->where('name', 'like', '%' . $request->get('keyword') . '%')
                ->orWhere('price', 'like', "%$request->get('keyword')%")
                ->orWhere('description', 'like', "%$request->get('keyword')%");
        }

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->get('name') . '%');
        }
        
        if ($request->has('price')) {
            $query->where('price', 'like', '%' . $request->get('price') . '%');
        }

        $services = $query->paginate(10);

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
