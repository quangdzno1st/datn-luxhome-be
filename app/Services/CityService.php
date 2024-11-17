<?php

namespace App\Services;

use App\Http\Requests\BaseSearchRequest;

interface CityService
{
    public function createNewCity($data);
    public function updateCity($data, $id);
    public function deleteCity($id);
    public function restoreCity($id);
    public function forceDeleteCity($id);
    public function searchByPage(BaseSearchRequest $request);
}