<?php

namespace App\Services;

use App\Http\Requests\RoomRequest;
use App\Http\Requests\RoomSearchRequest;

interface RoomService
{
    public function create(RoomRequest $request);

    public function update($id, RoomRequest $request);

    public function detail($id);

    public function delete($id);

    public function searchByPage(RoomSearchRequest $request, $hotelId);

    public function getRoomIdsNotAvailable($hotelId);

}