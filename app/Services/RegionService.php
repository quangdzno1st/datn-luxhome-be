<?php

namespace App\Services;

interface RegionService
{
    public function createNewReigon($data);
    public function updateReigon($data, $id);
    public function deleteReigon($id);
    public function restoreReigon($id);
    public function forceDeleteReigon($id);
}