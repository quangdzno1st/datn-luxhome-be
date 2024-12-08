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
use App\Models\User;
use App\Repositories\Room\RoomRepository;
use App\Repositories\User\UserRepository;
use App\Services\CatalogueRoomService;
use App\Services\CommonKeyCodeService;
use App\Services\RoomService;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

class UserServiceImpl implements UserService
{

    private UserRepository $userRepository;
    private CatalogueRoomService $catalogueRoomService;
    private CommonKeyCodeService $commonKeyCodeService;


    public function __construct(UserRepository       $userRepository,
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


    public function create($request)
    {
        $data = $request->all();
        $data["password"] = Hash::make($data["password"]);
        $data["type"] = $data["type"] ?? User::CUSTOMER;
        $data["group_id"] = $data["type"] ;
        $data["total_amount_ordered"] = 0;

        if ($request->has('avatar')) {
            $data['avatar'] = Storage::put('users', $data['avatar']);
        }

        $this->userRepository->create($data);
    }


    public function update($id, $request)
    {
        $user = User::find($id);
        $data = $request->all();
        $data["type"] = $data["type"] ?? $user->type;
        $data["group_id"] = $data["type"];
        $user = $this->detail($id);
        if ($request->has('avatar')) {
            if ($user->avatar) {
                Storage::delete($user->avatar);
            }

            $data['avatar'] = Storage::put('users', $data['avatar']);
        }
        return $this->userRepository->edit($user, $data);
    }

    public function detail($id)
    {
        $user = $this->userRepository->find($id);
        if (is_null($user)) {
            throw new RespException(__('message.room_not_found'));
        }

        return $user;
    }

    /**
     * @throws RespException
     */
    public function delete($id)
    {
        $user = $this->detail($id);
        $this->userRepository->delete($user);
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