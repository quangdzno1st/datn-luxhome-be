<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\BaseSearchRequest;
use App\Http\Requests\RoomRequest;
use App\Http\Requests\RoomSearchRequest;
use App\Services\RoomService;
use Illuminate\Http\Request;

class RoomController extends Controller
{

    private RoomService $roomService;

    /**
     * @param RoomService $roomService
     */
    public function __construct(RoomService $roomService)
    {
        $this->roomService = $roomService;
    }


    /**
     * Display a listing of the resource.
     *
     */
    public function index(RoomSearchRequest $request)
    {
        $data = $this->roomService->searchByPage($request);
        return $this->sendSuccessData($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function store(RoomRequest $request)
    {
        $data = $this->roomService->create($request);
        return $this->createSuccess($data);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $data = $this->roomService->detail($id);
        return $this->sendSuccess($data);
    }

    public function edit(RoomRequest $request)
    {

    }

    public function update(RoomRequest $request, $id)
    {
        $data = $this->roomService->update($id, $request);
        return $this->updateSuccess($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->roomService->delete($id);
        return $this->deleteSuccess();
    }
}
