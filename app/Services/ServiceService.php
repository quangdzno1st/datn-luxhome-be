<?php

namespace App\Services;

interface ServiceService
{
    public function getAllServices();
    public function getServiceById($id);
    public function createNewService($data);
    public function updateService($data, $id);
    public function deleteService($id);
    public function restoreService($id);
    public function forceDeleteService($id);
}