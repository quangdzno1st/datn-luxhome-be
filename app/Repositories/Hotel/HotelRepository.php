<?php

namespace App\Repositories\Hotel;

use App\Models\Hotel;
use App\Repositories\Base\BaseRepository;

class HotelRepository extends BaseRepository implements HotelInterface
{
    public function model(): string
    {
        return Hotel::class;
    }
}