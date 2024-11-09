<?php

namespace App\Services;

interface FileUploadService
{
    public function uploadImages(array $images, $objectId);

    public function uploadImage($image, $objectId);
    public function storeLocal($image);
}