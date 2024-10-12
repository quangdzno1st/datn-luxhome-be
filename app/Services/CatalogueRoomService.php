<?php

namespace App\Services;

use App\Http\Requests\CatalogueRequest;
use App\Http\Requests\CatalogueRoomSearchRequest;

interface CatalogueRoomService
{
    public function createOrUpdate($id, CatalogueRequest $request);

    public function delete($id);

    public function detail($id);

    public function searchByPage(CatalogueRoomSearchRequest $request);
}