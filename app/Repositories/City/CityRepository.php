<?php

namespace App\Repositories\City;

use App\Models\City;
use App\Repositories\Base\BaseRepository;

class CityRepository extends  BaseRepository
{
    public function model(): string
    {
        return City::class;
    }

    public function getAllCity(){
        $cities = $this->model->select('id', 'name', 'region_id')->latest('id')->get();

        return $cities;
    }

    public function detailCity($id){
        $cities = $this->model->where('id', $id)->select('id', 'name', 'region_id')->first();

        return $cities;
    }

    public function trash(){
        $cities = $this->model->onlyTrashed()->select('id', 'name', 'region_id')->latest('id')->get();

        return $cities;
    }

    public function getCities($id){
        $cities = $this->model->where('id', $id)->withTrashed()->select('id', 'name', 'region_id')->first();

        return $cities;
    }
}