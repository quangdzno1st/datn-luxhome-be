<?php

namespace App\Services;

use App\Http\Requests\RoomRequest;
use App\Http\Requests\RoomSearchRequest;

interface UserService
{
    public function create( $request);

    public function update($id,  $request);

    public function detail($id);

    public function delete($id);

    public function searchByPage(RoomSearchRequest $request);
}