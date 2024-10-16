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

    public function getAllServices()
    {
        $services = $this->serviceRepository->all();
        return $services;
    }
    public function getServiceById($id)
    {
        $service = $this->serviceRepository->first(['id' => $id]);
        return $service;
    }
    public function createNewService($data)
    {
        $service = $this->serviceRepository->create($data);
        return $service;
    }
    public function updateService($data, $id)
    {
        $model = $this->serviceRepository->find($id);
        $service = $this->serviceRepository->edit($model, $data);
        return $service;
    }
    public function deleteService($id)
    {
        $service = $this->serviceRepository->remove($id);
        return $service;
    }
    public function restoreService($id)
    {
        $service = $this->serviceRepository->retore($id);
        return $service;
    }
    public function forceDeleteService($id)
    {
        $service = $this->serviceRepository->destroy($id);
        return $service;
    }
}