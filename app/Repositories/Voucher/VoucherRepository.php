<?php

namespace App\Repositories\Voucher;

use App\Constant\Enum\ActiveStatusEnum;
use App\Models\Voucher;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        $userRank = Auth::user()->rank ?? 0;
        $dateNow = Carbon::now();
        $query = Voucher::query()
            ->where('vouchers.quantity', '>', 0)
            ->where(function ($query) use ($hotelId) {
                $query->where('vouchers.org_id', $hotelId)
                    ->orWhereNull('vouchers.org_id');
            })
            ->where(function ($query) use ($userRank) {
                $query->where('vouchers.conditional_rank', '>=', $userRank)
                    ->orWhereNull('vouchers.conditional_rank');
            })
            ->where('vouchers.conditional_total_amount', '<=', $orderTotalAmount)
            ->where('status', ActiveStatusEnum::Active->value)
            ->where(function ($query) use ($dateNow) {
                $query->where('vouchers.start_date', '<=', $dateNow)
                    ->where('vouchers.end_date', '>=', $dateNow);
            });

        return $query->get()->toArray();
    }
}