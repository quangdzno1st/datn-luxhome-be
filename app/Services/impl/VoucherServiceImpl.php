<?php

namespace App\Services\impl;

use App\Models\User;
use App\Models\Voucher;
use App\Repositories\Voucher\VoucherRepository;
use App\Services\VoucherService;
use Illuminate\Support\Facades\Auth;

class VoucherServiceImpl implements VoucherService
{
    protected VoucherRepository $voucherRepository;

    public function __construct(VoucherRepository $voucherRepository)
    {
        $this->voucherRepository = $voucherRepository;
    }

    public function listVoucher()
    {
        if (Auth::user()->type==User::ADMIN){
            $vouchers = Voucher::query()
                ->latest('created_at')
                ->paginate(10);
        }else{
            $vouchers = Voucher::query()
                ->latest('created_at')
                ->where('hotel_id','=',Auth::user()->org_id)
                ->orWhere('hotel_id',null)
                ->paginate(10);
        }
        return $vouchers;
    }

    public function showVoucher($id)
    {
        $voucher = $this->voucherRepository->first(['id' => $id]);
        return $voucher;
    }

    public function createVoucher($data)
    {
        $voucher = $this->voucherRepository->create($data);
        return $voucher;
    }

    public function updateVoucher($data, $id)
    {
        $model = $this->voucherRepository->find($id);
        $voucher = $this->voucherRepository->edit($model, $data);
        return $voucher;
    }

    public function deleteVoucher($id)
    {
        $voucher = $this->voucherRepository->remove($id);
        return $voucher;
    }

    public function restoreVoucher($id)
    {
        $voucher = $this->voucherRepository->retore($id);
        return $voucher;
    }

    public function forceDeleteVoucher($id)
    {
        $voucher = $this->voucherRepository->destroy($id);
        return $voucher;
    }

    public function getByCondition($key)
    {
        if ($key !== 'rank' && $key !== 'total-amount') {
            return null;
        }
        if ($key === 'rank') {
            $condition = [['conditional_rank', '!=', null]];
        } elseif ($key === 'total-amount') {
            $condition = [['conditional_total_amount', '!=', null]];
        }
        $vouchers = $this->voucherRepository->getWhere($condition);
        return $vouchers;
    }

    public function getMapByCode($voucherCodes)
    {
        $vouchers = $this->voucherRepository->getAllByCodeIn($voucherCodes);
        return collect($vouchers)->mapWithKeys(function ($item) {
            return [$item['code'] => $item];
        });
    }
}