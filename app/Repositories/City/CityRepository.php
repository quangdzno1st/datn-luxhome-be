<?php

namespace App\Repositories\City;

use App\Http\Requests\BaseSearchRequest;
use App\Models\City;
use App\Repositories\Base\BaseRepository;

class CityRepository extends BaseRepository
{
    public function model(): string
    {
        return City::class;
    }

    public function getAllCity()
    {
        return $this->model->select('id', 'name', 'region_id', 'thumbnail')->with('region')->orderBy('name')->paginate(10);
    }

    public function searchByPage(BaseSearchRequest $request)
    {
        $cities = $this->model->select("cities.id", "cities.name", "region_id", "cities.thumbnail")
            ->withCount('hotels as hotel_qty')
            ->orderBy('name');

        if ($request->has('keyword')) {
            $cities->where('name', 'like', '%' . $request->get('keyword') . '%');
        }

        return $cities->with('region')->get();
    }


    public function detailCity($id)
    {
        $cities = $this->model->where('id', $id)->select('id', 'name', 'region_id', 'thumbnail')->with('region')->first();

        return $cities;
    }

    public function trash()
    {
        $cities = $this->model->onlyTrashed()->select('id', 'name', 'region_id')->with('region')->latest('id')->paginate(10);

        return $cities;
    }

    public function getCities($id)
    {
        $cities = $this->model->where('id', $id)->withTrashed()->select('id', 'name', 'region_id')->with('region')->first();

        return $cities;
    }

}