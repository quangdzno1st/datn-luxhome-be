<?php

namespace App\Services;

use App\Http\Requests\CatalogueRequest;
use App\Http\Requests\CatalogueRoomSearchRequest;
use Illuminate\Http\Request;


interface CatalogueRoomService
{
    public function createOrUpdate($id, CatalogueRequest $request);

    public function delete($id);

    public function detail($id);

    public function existsById($id);

    public function searchByPage(Request $request);

    public function getAllByOrgId($orgId);
}