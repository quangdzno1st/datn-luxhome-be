<?php

namespace App\Services\impl;

use App\Repositories\Service\ServiceRepository;
use App\Services\ServiceService;

class ServiceServiceImpl implements ServiceService
{
    protected ServiceRepository $serviceRepository;

    public function __construct(ServiceRepository $serviceRepository) {
        $this->serviceRepository = $serviceRepository;
    }

    public function getAll()
    {
        $services = $this->serviceRepository->all();
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