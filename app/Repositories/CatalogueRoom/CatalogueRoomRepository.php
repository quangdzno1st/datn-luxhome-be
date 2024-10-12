<?php

namespace App\Repositories\CatalogueRoom;

use App\Models\CatalogueRoom;
use App\Repositories\Base\BaseRepository;

class CatalogueRoomRepository extends BaseRepository implements CatalogueRoomInterface
{
    public function model(): string
    {
        return CatalogueRoom::class;
    }

}