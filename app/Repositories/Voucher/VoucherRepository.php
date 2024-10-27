<?php

namespace App\Repositories\Voucher;

use App\Repositories\Base\BaseRepository;
use App\Models\Voucher;

class VoucherRepository extends BaseRepository implements VoucherInterface
{

    public function model(): string
    {
        return Voucher::class;
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

    public function getWhere(array $condition)
    {
        $query = $this->model;
        $this->resetModel();
        return $query->where($condition)->get();
    }
}