<?php

namespace App\Http\Controllers\Admin;

use App\Constant\Enum\AttributeCodeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\CatalogueRequest;
use App\Models\Hotel;
use App\Services\AttributeValueService;
use App\Services\CatalogueRoomService;
use App\Services\HotelService;
use Illuminate\Http\Request;

class CatalogueRoomController extends Controller
{

    private CatalogueRoomService $catalogueRoomService;
    private AttributeValueService $attributeValueService;
    private HotelService $hotelService;

    public function __construct(CatalogueRoomService  $catalogueRoomService,
                                AttributeValueService $attributeValueService,
    HotelService $hotelService)
    {
        $this->catalogueRoomService = $catalogueRoomService;
        $this->attributeValueService = $attributeValueService;
        $this->hotelService = $hotelService;
    }

    public function index()
    {

    }


    public function create()
    {
        $user = auth()->user();
        $hotel = $this->hotelService->getNonNullByID($user["org_id"]);
        $attributeValues = $this->attributeValueService->getAttributeFetchJoinValueBy([AttributeCodeEnum::NOI_THAT->value], $user['org_Id']);
        return view('admin.catalogue_rooms.create')->with(compact('hotel', 'attributeValues'));
    }


    public function store(CatalogueRequest $request)
    {
        $catalogueRoom = $this->catalogueRoomService->createOrUpdate(null, $request);
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
