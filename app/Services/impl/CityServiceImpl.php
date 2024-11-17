<?php

namespace App\Services\impl;

use App\Http\Requests\BaseSearchRequest;
use App\Repositories\City\CityRepository;
use App\Services\CityService;
use Illuminate\Support\Facades\DB;

class CityServiceImpl implements CityService
{
    private $cityRepo;

    public function __construct(CityRepository $cityRepo)
    {
        $this->cityRepo = $cityRepo;
    }

    public function createNewCity($data)
    {
        DB::beginTransaction();
        try {
            $existsRegionID = $this->cityRepo->getAllCity();

            foreach ($existsRegionID as $regionID) {
                if ((string)$regionID == (string)$data['region_id']) {
                    throw new \Exception('ID has been exists');
                }
            }

            $city = $this->cityRepo->create($data);
            DB::commit();

            return $city;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateCity($data, $id)
    {
        DB::beginTransaction();

        try {
            $city = $this->cityRepo->detailCity($id);

            if ($city === null) {
                throw new \Exception('City not found');
            }

            $existsRegionID = $this->cityRepo->getAllCity();

            foreach ($existsRegionID as $regionID) {
                if ((string)$regionID == (string)$data['region_id']) {
                    throw new \Exception('ID has been exists');
                }
            }

            $city->update($data);

            DB::commit();

            return $city;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteCity($id)
    {
        try {
            $city = $this->cityRepo->detailCity($id);

            if ($city === null) {
                throw new \Exception('City not found');
            }

            $city->delete();

            return $city;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function restoreCity($id)
    {
        DB::beginTransaction();

        try {
            $city = $this->cityRepo->getCities($id);

            $city->restore();

            DB::commit();

            return $city;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function forceDeleteCity($id)
    {
        try {
            $city = $this->cityRepo->getCities($id);

            $city->forceDelete();

            DB::commit();

        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function searchByPage(BaseSearchRequest $request)
    {
        return $this->cityRepo->searchByPage($request);
    }

}