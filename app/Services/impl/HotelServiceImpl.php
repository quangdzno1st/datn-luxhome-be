<?php

namespace App\Services\impl;

use App\Models\Image;
use App\Repositories\Hotel\HotelRepository;
use App\Services\HotelService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class HotelServiceImpl implements HotelService
{
    private HotelRepository $hotelRepos;

    const PATH_UPLOAD = 'hotels';

    public function __construct(HotelRepository $hotelRepos)
    {
        $this->hotelRepos = $hotelRepos;
    }

    public function createNewHotel($data)
    {
        try {
            $data['thumbnail'] = Storage::put(self::PATH_UPLOAD, $data['thumbnail']);
            $hotel = $this->hotelRepos->create($data);
            $dataImage = [];

            if (isset($data['images']) && is_array($data['images'])) {

                foreach ($data['images'] as $image) {
                    $path = $image->store(self::PATH_UPLOAD, 'public');

                    $dataImage[] = [
                        'id' => \Ramsey\Uuid\Uuid::uuid4(),
                        'path' => $path,
                        'alt' => $image->getClientOriginalName(),
                        'object_id' => $hotel->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                if (!empty($dataImage)) {
                    Image::insert($dataImage);
                }
            }

            return $hotel;
        } catch (\Exception $e) {
            throw $e;
        }
    }


    public function updateHotel($data, $id)
    {
        try {
            $hotel = $this->getNonNullByID($id);

            if(isset($data['thumbnail'])) {
                $data['thumbnail'] = Storage::put(self::PATH_UPLOAD, $data['thumbnail']);

                if(Storage::exists($hotel->thumbnail) && $hotel->thumbnail) {
                    Storage::delete($hotel->thumbnail);
                }
            } else {
                $data['thumbnail'] = $hotel->thumbnail;
            }

            $hotel->update($data);

            if (isset($data['images']) && is_array($data['images'])) {
                $dataImage = [];

                // if ($hotel->images) {
                //     foreach ($hotel->images as $image) {
                //         if (!empty($image->path)) {
                //             Storage::disk('public')->delete($image->path);
                //         }
                //         $image->delete();
                //     }
                // }


                foreach ($data['images'] as $image) {
                    $path = $image->store(self::PATH_UPLOAD, 'public');

                    $dataImage[] = [
                        'id' => \Ramsey\Uuid\Uuid::uuid4(),
                        'path' => $path,
                        'alt' => $image->getClientOriginalName(),
                        'object_id' => $hotel->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                if (!empty($dataImage)) {
                    Image::insert($dataImage);
                }
            }

            return $hotel;
        } catch (\Exception $e) {
            throw $e;
        }
    }


    public function deleteHotel($id)
    {
        try {
            $hotel = $this->getNonNullByID($id);

            $hotel->delete();

            return $hotel;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function restoreHotel($id)
    {
        try {
            $hotel = $this->getNonNullByID($id);

            $hotel->restore();

            return $hotel;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function forceDeleteHotel($id)
    {
        try {
            $hotel = $this->getNonNullByID($id);

            $hotel->forceDelete();

            return $hotel;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getNonNullByID($id)
    {
        $hotel = $this->hotelRepos->detailHotel($id);

        if ($hotel === null) {
            throw new \Exception('Khách sạn không tồn tại hoặc đã bị xóa');
        }

        return $hotel;
    }
}