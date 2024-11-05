<?php

namespace App\Repositories\Reigion;

use App\Models\Region;
use App\Repositories\Base\BaseRepository;

class RegionRepository extends BaseRepository
{
    public function model(): string
    {
        return Region::class;
    }

    // chua xoa
    public function getAllRegion(){
        $regions = $this->model->select('id', 'name')->latest('id')->paginate(10);

        return $regions;
    }

    public function regionDetail($id){
        $region = $this->model->where('id', $id)->select('id', 'name')->first();

        return $region;
    }

    // da xoa
    public function trash(){
        $regions = $this->model->onlyTrashed()->select('id', 'name')->latest('id')->paginate(10);

        return $regions;
    }

    public function getRegions($id){
        $regions = $this->model->withTrashed()->where('id', $id)->select('id', 'name')->first();

        return $regions;
    }
}