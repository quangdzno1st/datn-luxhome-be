<?php

namespace App\Services\impl;

use App\Models\Image;
use App\Repositories\image\ImageRepository;
use App\Services\FileUploadService;
use Illuminate\Support\Str;

class FileUploadServiceImpl implements FileUploadService
{
    private ImageRepository $imageRepos;

    /**
     * @param ImageRepository $imageRepos
     */
    public function __construct(ImageRepository $imageRepos)
    {
        $this->imageRepos = $imageRepos;
    }


    public function uploadImages(array $images, $objectId): void
    {
        if (sizeof($images) <= 0) {
            return;
        }

        $imagesData = [];
        foreach ($images as $image) {
            $this->handleStorageImage($imagesData, $image, $objectId);
        }

        if (!empty($imagesData)) {
            Image::query()->insert($imagesData);
        }
    }

    public function handleStorageImage(&$imagesData, $image, $objectId): void
    {
        if (empty($image)) {
            return;
        }

        $path = $this->storeLocal($image);
        $imagesData[] = [
            'id' =>  Str::uuid()->toString(),
            'path' => $path,
            'object_id' => $objectId,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function storeLocal($image)
    {
        $newPath = time() . "." . $image->getClientOriginalExtension();
        return $image->storeAs('images', $newPath, 'public');
    }

    public function uploadImage($image, $objectId): void
    {
        $imagesData = [];
        $this->handleStorageImage($imagesData, $image, $objectId);
        Image::query()->insert($imagesData);
    }
}