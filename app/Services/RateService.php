<?php

namespace App\Services;

interface RateService
{
    public function listRate($numRecord);
    public function createRate($data);
    public function showRate($id);
    public function deleteRate($id);
    public function restoreRate($id);
    public function forceDeleteRate($id);
}