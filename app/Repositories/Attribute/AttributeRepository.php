<?php

namespace App\Repositories\Attribute;

use App\Models\Attribute;
use App\Repositories\Base\BaseRepository;

class AttributeRepository extends BaseRepository implements AttributeValueInterface
{

    public function model(): string
    {
        return Attribute::class;
    }
}