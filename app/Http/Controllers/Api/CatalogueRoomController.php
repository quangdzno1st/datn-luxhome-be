<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CatalogueRequest;
use App\Services\CatalogueRoomService;
use Illuminate\Http\Request;


class CatalogueRoomController extends Controller
{

    protected CatalogueRoomService $catalogueRoomService;

    public function __construct(CatalogueRoomService $catalogueRoomService)
    {
        $this->catalogueRoomService = $catalogueRoomService;
    }

    public function index()
    {
//       $data = $this->catalogueRoomService->searchByPage();
    }

    public function store(CatalogueRequest $request)
    {
        $data = $this->catalogueRoomService->createOrUpdate(null, $request);
        return $this->createSuccess($data);
    }

    public function show($id)
    {
        $data = $this->catalogueRoomService->detail($id);
        return $this->sendSuccess($data);
    }

    public function update(CatalogueRequest $request, $id)
    {
        $data = $this->catalogueRoomService->createOrUpdate($id, $request);
        return $this->updateSuccess($data);
    }


    public function destroy($id)
    {
        $this->catalogueRoomService->delete($id);
        return $this->deleteSuccess();
    }

    public function search(Request $request)
    {
        $data = $this->catalogueRoomService->search($request);
        return $this->sendSuccess($data);
    }
}
