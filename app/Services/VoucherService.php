<?php

namespace App\Services;

interface VoucherService
{
    public function listVoucher();
    public function createVoucher($data);
    public function showVoucher($id);
    public function updateVoucher($data,$id);
    public function deleteVoucher($id);
    public function restoreVoucher($id);
    public function forceDeleteVoucher($id);
}