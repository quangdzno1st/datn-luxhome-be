<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomRequest;
use App\Http\Requests\RoomSearchRequest;
use App\Models\Hotel;
use App\Models\User;
use App\Repositories\Hotel\HotelRepository;
use App\Services\CatalogueRoomService;
use App\Services\RoomService;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    private RoomService $roomService;
    private CatalogueRoomService $catalogueRoomService;
    private HotelRepository $hotelRoomService;


    public function __construct(RoomService $roomService, CatalogueRoomService $catalogueRoomService)
    {
        $this->roomService = $roomService;
        $this->catalogueRoomService = $catalogueRoomService;
    }


    public function index(RoomSearchRequest $request)
    {
        $hotelId = $request->hotel_id ?? Auth::user()->org_id;

        if (\auth()->user()->type != User::ADMIN) {
            if (auth()->user()->org_id != $hotelId) {
                abort(403);
            }
        }

        $hotels = Hotel::all();
        $rooms = $this->roomService->searchByPage($request, $hotelId);
        $roomBookedIds = $this->roomService->getRoomIdsNotAvailable($hotelId);
        $catalogueRooms = $this->catalogueRoomService->getAllByOrgId($hotelId);
        return view('admin.rooms.index', compact('rooms', 'hotels', "catalogueRooms", 'roomBookedIds', 'hotelId'));
    }


    public function store(RoomRequest $request)
    {
        $this->roomService->create($request);
        return redirect()->back()->with('success', 'Thêm mới thành công!');
    }


    public function update(RoomRequest $request, $id)
    {
        $this->roomService->update($id, $request);
        return redirect()->back()->with('success', 'Cập nhật thành công!');
    }


    public function destroy($id)
    {
        $this->roomService->delete($id);
        return redirect()->back()->with('success', 'Xóa thành công!');
    }
}
