<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomRequest;
use App\Http\Requests\RoomSearchRequest;
use App\Services\CatalogueRoomService;
use App\Services\RoomService;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    private RoomService $roomService;
    private CatalogueRoomService $catalogueRoomService;


    public function __construct(RoomService $roomService, CatalogueRoomService $catalogueRoomService)
    {
        $this->roomService = $roomService;
        $this->catalogueRoomService = $catalogueRoomService;
    }


    public function index(RoomSearchRequest $request)
    {
        $rooms = $this->roomService->searchByPage($request);
        $catalogueRooms = $this->catalogueRoomService->getAllByOrgId("4688497a-ca83-4027-a0fc-0929369f9a8d");
        return view('admin.rooms.index', compact('rooms', "catalogueRooms"));
    }


    public function store(RoomRequest $request)
    {
        $this->roomService->create($request);
        return redirect()->route('rooms.index');
    }


    public function update(RoomRequest $request, $id)
    {
        $this->roomService->update($id, $request);
        return redirect()->route('rooms.index');
    }


    public function destroy($id)
    {
        $this->roomService->delete($id);
        return redirect()->route('rooms.index');
    }
}
