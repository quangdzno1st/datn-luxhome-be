<?php

namespace App\Services\impl;

use App\Constant\Enum\HttpStatusCodeEnum;
use App\Constant\Enum\RoomStatusEnum;
use App\Constant\Enum\TypeCodeEnum;
use App\Exceptions\RespException;
use App\Helpers\Constant;
use App\Http\Requests\RoomRequest;
use App\Http\Requests\RoomSearchRequest;
use App\Models\Room;
use App\Repositories\Room\RoomRepository;
use App\Repositories\User\UserRepository;
use App\Services\CatalogueRoomService;
use App\Services\CommonKeyCodeService;
use App\Services\RoomService;
use Illuminate\Support\Facades\Request;

class UserServiceImpl implements RoomService
{

    private UserRepository $userRepository;
    private CatalogueRoomService $catalogueRoomService;
    private CommonKeyCodeService $commonKeyCodeService;


    public function __construct(UserRepository $userRepository,
                                CatalogueRoomService $catalogueRoomService,
                                CommonKeyCodeService $commonKeyCodeService)
    {
        $this->userRepository = $userRepository;
        $this->catalogueRoomService = $catalogueRoomService;
        $this->commonKeyCodeService = $commonKeyCodeService;
    }

    public function index(Request $request)
    {
        return $this->userRepository->getAll($request);
    }

    /**
     * @throws RespException
     */
    public function create(RoomRequest $request)
    {
        $data = $request->validated();

        $this->validateBeforeSave($data);

        $data["code"] = $this->commonKeyCodeService->genNewKeyCode(TypeCodeEnum::ROOM_TYPE->value,
            Constant::STRING_6_CHAR, $data['org_id']);

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
            throw new RespException(__('messages.room_status_invalid'), HttpStatusCodeEnum::INVALID_VALUE->value);
        }
    }

    /**
     * @throws RespException
     */
    private function validateCatalogueRoom($id): void
    {
        $existsCatalogueRoom = $this->catalogueRoomService->existsById($id);
        if (!$existsCatalogueRoom) {
            throw new RespException(__('messages.catalogue_room_not_found'), HttpStatusCodeEnum::NOT_FOUND->value);
        }
    }

    /**
     * @throws RespException
     */
    public function update($id, RoomRequest $request)
    {
        $data = $request->validated();

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
            throw new RespException(__('message.room_not_found'), HttpStatusCodeEnum::NOT_FOUND->value);
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

    public function searchByPage(RoomSearchRequest $request)
    {
        $query = Room::query();

        if ($request->has('keyword')) {
            $query->where('code', 'like', '%' . $request->get('keyword') . '%');
        }
        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }
        if ($request->has('catalogue_room_id')) {
            $query->where('catalogue_room_id', $request->get('catalogue_room_id'));
        }
        if ($request->has('org_id')) {
            $query->where('org_id', $request->get('org_id'));
        }

        $query->join("catalogue_rooms as c", "rooms.catalogue_room_id", "=", "c.id");
        $query->join("hotels as h", "c.hotel_id", "=", "h.id");

        $query->select("rooms.*", "c.name as catalogue_room_name", "h.name as hotel_name")
            ->orderBy("rooms.code");

        $page = $request->getPage();
        $perPage = $request->getPerPage();


        return $query->paginate($perPage, ['*'], 'page', $page);
    }
}