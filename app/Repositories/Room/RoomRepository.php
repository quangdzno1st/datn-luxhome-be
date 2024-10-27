<?php

namespace App\Repositories\Room;

use App\Models\Room;
use App\Repositories\Base\BaseRepository;

class RoomRepository extends BaseRepository implements RoomInterface
{

    public function model(): string
    {
        return Room::class;
    }

    public function getById(string $id): ?Room
    {
        $query = $this->model::query();
        $query->select("rooms.*", "c.name as catalogue_room_name", "h.name as hotel_name");

        $query->join("catalogue_rooms as c", "rooms.catalogue_room_id", "=", "c.id");
        $query->join("hotels as h", "c.hotel_id", "=", "h.id");

        $query->where("rooms.id", $id);

        return $query->first();
    }
}