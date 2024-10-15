<?php

namespace App\Services\impl;

use App\Repositories\Voucher\VoucherRepository;
use App\Services\VoucherService;

class VoucherServiceImpl implements VoucherService
{
    protected VoucherRepository $voucherRepository;

    public function __construct(VoucherRepository $voucherRepository) {
        $this->voucherRepository = $voucherRepository;
    }

    public function listVoucher()
    {
        $services = $this->voucherRepository->all();
        return $services;
    }
    public function showVoucher($id)
    {
        $service = $this->voucherRepository->first(['id' => $id]);
        return $service;
    }
    public function createVoucher($data)
    {
        $service = $this->voucherRepository->create($data);
        return $service;
    }
    public function updateVoucher($data, $id)
    {
        $model = $this->voucherRepository->find($id);
        $service = $this->voucherRepository->edit($model, $data);
        return $service;
    }
    public function deleteVoucher($id)
    {
        $service = $this->voucherRepository->remove($id);
        return $service;
    }
    public function restoreVoucher($id)
    {
        $service = $this->voucherRepository->retore($id);
        return $service;
    }
    public function forceDeleteVoucher($id)
    {
        $service = $this->voucherRepository->destroy($id);
        return $service;
    }
}