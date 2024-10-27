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

    public function existsById($id): bool
    {
        return CatalogueRoom::query()->where('id', $id)->exists();
    }

}