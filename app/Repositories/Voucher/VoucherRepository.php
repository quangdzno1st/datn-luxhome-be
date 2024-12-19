<?php

namespace App\Repositories\Voucher;

use App\Constant\Enum\ActiveStatusEnum;
use App\Models\Voucher;
use App\Models\Wallet;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Carbon;
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
                $query->where("hotel_id", $orgId)
                    ->orWhereNull("hotel_id");
            });
    }

    public function getAllForOrder($orderTotalAmount, $hotelId, $userId)
    {
        $dateNow = Carbon::now()->startOfDay();
        $subQuery = Voucher::query()
            ->select('vouchers.id as voucher_id')
            ->join('wallets as w', 'w.voucher_id', "vouchers.id")
            ->where('w.user_id', '=', $userId)
            ->where('status', ActiveStatusEnum::Active->value)
            ->where('start_date', '<=', $dateNow)
            ->where(function ($query) use ($dateNow) {
                $query->where('start_date', '<=', $dateNow)
                    ->orWhereNull('start_date');
            })
            ->where(function ($query) use ($dateNow) {
                $query->where('end_date', '>=', $dateNow)
                    ->orWhereNull('end_date');
            })
            ->where('quantity', '>', 0)
            ->where('conditional_total_amount', '<=', $orderTotalAmount)
            ->where(function ($query) use ($hotelId) {
                $query->where('hotel_id', $hotelId)
                    ->orWhereNull('hotel_id');
            });

        return Wallet::query()
            ->join('vouchers as v', 'v.id', 'wallets.voucher_id')
            ->leftJoinSub($subQuery, 'vi', function ($join) {
                $join->on('vi.voucher_id', '=', 'wallets.voucher_id');
            })
            ->where('wallets.user_id', $userId)
            ->where('v.quantity', '>', 0)
            ->where(function ($query) use ($dateNow) {
                $query->where('end_date', '>=', $dateNow)
                    ->orWhereNull('end_date');
            })
            ->select('v.*', 'v.id as voucher_id', DB::raw("if(vi.voucher_id is null, 2, 1) as isValid"))
            ->get()->toArray();
    }

    public function getInvalidVoucherByUserIdAndVoucherId($voucherId, $hotelId, $orderTotalAmount, $userId)
    {

        $dateNow = Carbon::now()->startOfDay();
        return Voucher::query()
            ->select('vouchers.id as voucher_id', 'vouchers.discount_type', 'vouchers.discount_value', 'vouchers.max_price')
            ->join('wallets as w', 'w.voucher_id', "vouchers.id")
            ->where('w.user_id', '=', $userId)
            ->where('status', ActiveStatusEnum::Active->value)
            ->where(function ($query) use ($dateNow) {
                $query->whereRaw('DATE(vouchers.end_date) >= ?', [$dateNow])
                    ->orWhereNull('vouchers.end_date');
            })
            ->where('quantity', '>', 0)
            ->where('conditional_total_amount', '<=', $orderTotalAmount)
            ->where('vouchers.id', $voucherId)
            ->where(function ($query) use ($hotelId) {
                $query->where('hotel_id', $hotelId)
                    ->orWhereNull('hotel_id');
            })->get()->toArray();
    }

    public function getAllByCodeIn($codes)
    {
        $dateNow = Carbon::now()->format('Y-m-d');

        $query = Voucher::query()
            ->whereIn('code', $codes)
            ->where('status', ActiveStatusEnum::Active->value)
            ->where('quantity', '>', 0)
            ->where(function ($query) use ($dateNow) {
                $query->whereRaw('DATE(vouchers.end_date) >= ?', [$dateNow])
                    ->orWhereNull('vouchers.end_date');
            });

        return $query->get()->toArray();

    }
}