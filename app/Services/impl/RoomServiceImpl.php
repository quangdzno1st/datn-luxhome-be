<?php

namespace App\Services\impl;

use App\Constant\Enum\RoomStatusEnum;
use App\Constant\Enum\TypeCodeEnum;
use App\Exceptions\RespException;
use App\Helpers\Constant;
use App\Http\Requests\RoomRequest;
use App\Http\Requests\RoomSearchRequest;
use App\Models\Room;
use App\Repositories\Room\RoomRepository;
use App\Services\CatalogueRoomService;
use App\Services\CommonKeyCodeService;
use App\Services\RoomService;
use Illuminate\Support\Facades\Auth;

class RoomServiceImpl implements RoomService
{

    private RoomRepository $roomRepos;
    private CatalogueRoomService $catalogueRoomService;
    private CommonKeyCodeService $commonKeyCodeService;

    /**
     * @param RoomRepository $roomRepos
     */
    public function __construct(RoomRepository       $roomRepos,
                                CatalogueRoomService $catalogueRoomService,
                                CommonKeyCodeService $commonKeyCodeService)
    {
        $this->roomRepos = $roomRepos;
        $this->catalogueRoomService = $catalogueRoomService;
        $this->commonKeyCodeService = $commonKeyCodeService;
    }


    /**
     * @throws RespException
     */
    public function create(RoomRequest $request)
    {
        $data = $request->validated();

        $this->validateBeforeSave($data);

        $data["code"] = $this->commonKeyCodeService->genNewKeyCode(TypeCodeEnum::ROOM_TYPE->value,
            Constant::STRING_6_CHAR,$request->hotel_id ?? auth()->user()->org_id);
        $data['hotel_id'] = $request->hotel_id ?? Auth::user()->org_id;

        return $this->roomRepos->create($data);
    }

    /**
     * @throws RespException
     */
    private function validateBeforeSave(array $data): void
    {
        $this->validateStatus($data['status']);
        $this->validateCatalogueRoom($data['catalogue_room_id']);
    }

    /**
     * @throws RespException
     */
    private function validateStatus($status): void
    {
        if (!RoomStatusEnum::isConstant($status)) {
            throw new RespException(__('messages.room_status_invalid'));
        }
    }

    /**
     * @throws RespException
     */
    private function validateCatalogueRoom($id): void
    {
        $existsCatalogueRoom = $this->catalogueRoomService->existsById($id);
        if (!$existsCatalogueRoom) {
            throw new RespException(__('messages.catalogue_room_not_found'));
        }
    }

    /**
     * @throws RespException
     */
    public function update($id, RoomRequest $request)
    {
        $data = $request->validated();
        unset($data['orgId']);

        $this->validateBeforeSave($data);

        $room = $this->detail($id);

        return $this->roomRepos->edit($room, $data);
    }

    /**
     * @throws RespException
     */
    public function detail($id)
    {
        $room = $this->roomRepos->getById($id);
        if (is_null($room)) {
            throw new RespException(__('messages.room_not_found'));
        }

        return $room;
    }

    /**
     * @throws RespException
     */
    public function delete($id)
    {
        $room = $this->detail($id);
        $this->roomRepos->delete($room);
    }

    public function searchByPage(RoomSearchRequest $request, $hotelId)
    {
        $query = Room::query();

        if ($request->has('keyword')) {
            $query->where(function ($query) use ($request) {
                $query->where('code', 'like', '%' . $request->get('keyword') . '%')
                    ->orWhere("c.name", "like", "%" . $request->get('keyword') . "%");
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }
        if ($request->has('catalogue_room_id') && $request->get('catalogue_room_id') != "0") {
            $query->where('catalogue_room_id', $request->get('catalogue_room_id'));
        }
        if ($hotelId) {
            $query->where('rooms.hotel_id', $hotelId);
        }

        $query->join("catalogue_rooms as c", "rooms.catalogue_room_id", "=", "c.id");
        $query->join("hotels as h", "c.hotel_id", "=", "h.id");

        $query->select("rooms.*", "c.name as catalogue_room_name", "h.name as hotel_name")
            ->orderByDesc("rooms.code");

        $page = $request->getPage();
        $perPage = $request->getPerPage();


        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    public function getRoomIdsNotAvailable($hotelId)
    {
        return $this->roomRepos->getRoomBookedIdToday($hotelId);
    }


}