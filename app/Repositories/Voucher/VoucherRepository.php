<?php

namespace App\Repositories\Voucher;

use App\Constant\Enum\ActiveStatusEnum;
use App\Models\Voucher;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Carbon;

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

    public function existsByIdAndOrgId($id, $orgId)
    {
        return Voucher::query()->where("id", $id)
            ->where(function ($query) use ($orgId) {
                $query->where("org_id", $orgId)
                    ->orWhereNull("org_id");
            });
    }

    public function getAllForOrder($orderTotalAmount, $hotelId)
    {
        $dateNow = Carbon::now();
        $query = Voucher::query()
            ->where('vouchers.quantity', '>', 0)
            ->where(function ($query) use ($hotelId) {
                $query->where('vouchers.hotel_id', $hotelId)
                    ->orWhereNull('vouchers.hotel_id');
            })
            ->where('status', ActiveStatusEnum::Active->value)
            ->where(function ($query) use ($dateNow) {
                $query->where('vouchers.start_date', '<=', $dateNow)
                    ->where('vouchers.end_date', '>=', $dateNow);
            });

        return $query->get()->toArray();
    }

    public function getAllByCodeIn($codes)
    {
        $dateNow = Carbon::now()->format('Y-m-d');

        $query = Voucher::query()
            ->whereIn('code', $codes)
            ->where('status', ActiveStatusEnum::Active->value)
            ->where(function ($query) use ($dateNow) {
                $query->whereRaw('DATE(vouchers.start_date) >= ?', [$dateNow])
                    ->orWhereNull('vouchers.start_date');
            });

        return $query->get()->toArray();

    }
}