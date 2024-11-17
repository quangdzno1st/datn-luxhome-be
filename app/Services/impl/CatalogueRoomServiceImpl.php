<?php

namespace App\Services\impl;

use App\Constant\Enum\HttpStatusCodeEnum;
use App\Exceptions\RespException;
use App\Http\Requests\CatalogueRequest;
use App\Models\CatalogueRoom;
use App\Repositories\CatalogueRoom\CatalogueRoomRepository;
use App\Repositories\Hotel\HotelRepository;
use App\Services\CatalogueRoomService;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;

class CatalogueRoomServiceImpl implements CatalogueRoomService
{
    private CatalogueRoomRepository $catalogueRoomRepos;
    private FileUploadService $fileUploadService;
    private HotelRepository $hotelRepo;

    /**
     * @param CatalogueRoomRepository $catalogueRoomRepos
     * @param HotelRepository $hotelRepo
     */
    public function __construct(CatalogueRoomRepository $catalogueRoomRepos,
                                HotelRepository         $hotelRepo,
                                FileUploadService       $fileUploadService,
    )
    {
        $this->catalogueRoomRepos = $catalogueRoomRepos;
        $this->hotelRepo = $hotelRepo;
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * @throws RespException
     */
    private function validateBeforeSave(array $request): void
    {
        $hotel = $this->hotelRepo->find($request['org_id']);
        if (!$hotel) {
            throw new RespException(__('messages.hotel_not_found'));
        }
    }

    /**
     * @throws RespException
     */
    public function createOrUpdate($id, CatalogueRequest $request)
    {
        $data = $request->validated();

        $data['org_id'] = auth()->user()->org_id;
        $data['status'] = empty($data['status']) ? 0 : 1;
        $data['thumbnail'] = $this->fileUploadService->storeLocal($request->file('thumbnail'));

        $this->validateBeforeSave($data);

        $catalogueRoom = $this->getCatalogueRoomBy($id);
        $catalogueRoom = $this->catalogueRoomRepos->edit($catalogueRoom, $data);
        $this->fileUploadService->uploadImages($request->file('images'), $catalogueRoom['id']);

        return $catalogueRoom;
    }

    /**
     * @throws RespException
     */
    private function getCatalogueRoomBy($id)
    {
        if (is_null($id)) {
            return new CatalogueRoom();
        }

        $catalogueRoom = $this->catalogueRoomRepos->find($id);

        if (is_null($catalogueRoom)) {
            throw new RespException(__('messages.catalogue_room_not_found'));
        }

        return $catalogueRoom;
    }

    /**
     * @throws RespException
     */
    public function delete($id): bool
    {
        $catalogueRoom = $this->getCatalogueRoomBy($id);
        return $this->catalogueRoomRepos->delete($catalogueRoom);
    }

    /**
     * @throws RespException
     */
    public function detail($id)
    {
        $catalogueRoom = $this->getCatalogueRoomBy($id);
        $this->incrementViewOrLike($id, 'view');

        return $catalogueRoom;
    }

    public function incrementViewOrLike($id, string $incrementField): void
    {
        $this->catalogueRoomRepos->increment(['id' => $id], $incrementField);
    }

    public function searchByPage(Request $request)
    {
        return $this->catalogueRoomRepos->searchByPage($request);
    }

    public function existsById($id): bool
    {
        return $this->catalogueRoomRepos->existsById($id);
    }

    public function getAllByOrgId($orgId)
    {
        return $this->catalogueRoomRepos->getAllByOrgId($orgId);
    }
}