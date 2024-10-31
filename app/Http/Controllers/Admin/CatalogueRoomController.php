<?php

namespace App\Http\Controllers\Admin;

use App\Constant\Enum\AttributeCodeEnum;
use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Services\AttributeValueService;
use App\Services\CatalogueRoomService;
use Illuminate\Http\Request;

class CatalogueRoomController extends Controller
{

    private CatalogueRoomService $catalogueRoomService;
    private AttributeValueService $attributeValueService;

    public function __construct(CatalogueRoomService  $catalogueRoomService,
                                AttributeValueService $attributeValueService)
    {
        $this->catalogueRoomService = $catalogueRoomService;
        $this->attributeValueService = $attributeValueService;
    }

    public function index()
    {

    }


    public function create()
    {
        $user = auth()->user();
        $hotels = Hotel::query()->orderBy('name')->get();
        $attributeValues = $this->attributeValueService->getAttributeFetchJoinValueBy([AttributeCodeEnum::NOI_THAT->value], "4688497a-ca83-4027-a0fc-0929369f9a8d");
        return view('admin.catalogue_rooms.create')->with(compact('hotels', 'attributeValues'));
    }


    public function store(Request $request)
    {
        dd($request->all());
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

    public function storeImage(Request $request)
    {
    }

}
