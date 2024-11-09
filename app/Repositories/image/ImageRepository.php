<?php

namespace App\Repositories\image;

use App\Models\Image;
use App\Repositories\Base\BaseRepository;

class ImageRepository extends BaseRepository implements ImageInterface
{
    public function model()
    {
        return Image::class;
    }

}