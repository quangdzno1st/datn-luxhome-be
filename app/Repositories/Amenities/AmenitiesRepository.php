<?php

namespace App\Repositories\Amenities;

use App\Models\Attribute;
use App\Repositories\Base\BaseRepository;
use App\Models\Service;

class AmenitiesRepository extends BaseRepository implements AmenitiesInterface
{

    public function model(): string
    {
        return Attribute::class;
    }

    public function remove($id)
    {
        $query = $this->model->find($id);
        $query->delete();
        return $query;
    }

    public function retore($id)
    {
        $query = $this->model->onlyTrashed()->where('id', $id)->first();
        $query->restore();
        return $query;

    }

    public function destroy($id)
    {
        $query = $this->model->withTrashed()->where('id', $id)->first();
        $query->forceDelete();
        return $query;
    }

}