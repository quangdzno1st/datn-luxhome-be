<?php

namespace App\Services\impl;

use App\Constant\Enum\HttpStatusCodeEnum;
use App\Exceptions\RespException;
use App\Http\Requests\CatalogueRequest;
use App\Http\Requests\CatalogueRoomSearchRequest;
use App\Models\CatalogueRoom;
use App\Repositories\CatalogueRoom\CatalogueRoomRepository;
use App\Repositories\Hotel\HotelRepository;
use App\Services\CatalogueRoomService;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;

class CatalogueRoomServiceImpl implements CatalogueRoomService
{
    private CatalogueRoomRepository $catalogueRoomRepos;
    private HotelRepository $hotelRepo;
    protected ?Uuid $orgId;

    /**
     * @param CatalogueRoomRepository $catalogueRoomRepos
     * @param HotelRepository $hotelRepo
     */
    public function __construct(CatalogueRoomRepository $catalogueRoomRepos,
                                HotelRepository         $hotelRepo
    )
    {
        $this->catalogueRoomRepos = $catalogueRoomRepos;
        $this->hotelRepo = $hotelRepo;
        $this->orgId = Auth::user()->org_id ?? null;
    }

    /**
     * @throws RespException
     */
    private function validateBeforeSave(array $request): void
    {
        $hotel = $this->hotelRepo->find($request['hotel_id']);

        if (!$hotel) {
            throw new RespException(__('messages.hotel_not_found'), HttpStatusCodeEnum::NOT_FOUND->value);
        }
    }

    /**
     * @throws RespException
     */
    public function createOrUpdate($id, CatalogueRequest $request)
    {
        $data = $request->validated();

//        if (isset($data['org_id'])) {
//            $data['org_id'] = $this->orgId;
//        }

        $this->validateBeforeSave($data);

        $catalogueRoom = $this->getCatalogueRoomBy($id);

        return $this->catalogueRoomRepos->edit($catalogueRoom, $data);
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
            throw new RespException(__('messages.catalogue_room_not_found'), HttpStatusCodeEnum::NOT_FOUND->value);
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

    public function searchByPage(CatalogueRoomSearchRequest $request): void
    {
        $data = $request->validated();

//        $catalogueRooms = $this->catalogueRoomRepos->paginate();
    }

}