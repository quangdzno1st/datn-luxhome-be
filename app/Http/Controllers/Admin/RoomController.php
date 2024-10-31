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


    public function create()
    {

    }


    public function store(Request $request)
    {

        dd($request->all());
        $data = $request->validated();
        $this->roomService->create($data);

        return redirect()->route('admin.rooms.index');
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
