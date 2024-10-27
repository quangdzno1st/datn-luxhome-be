<?php

namespace App\Services;

interface CityService
{
    public function createNewCity($data);
    public function updateCity($data, $id);
    public function deleteCity($id);
    public function restoreCity($id);
    public function forceDeleteCity($id);
}