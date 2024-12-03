<?php

namespace App\Http\Controllers\Admin;

use App\Models\Hotel;
use App\Models\Image;
use App\Models\Attribute;
use App\Repositories\CatalogueRoom\CatalogueRoomRepository;
use Illuminate\Http\Request;
use App\Models\CatalogueRoom;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\CatalogueRoomAttribute;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\CatalogueRoom\CreateRequest;
use App\Http\Requests\Admin\CatalogueRoom\UpdateRequest;

class CatalogueRoomController extends Controller
{
    private CatalogueRoomRepository $catalogueRoomRepos;


    const PATH_VIEW = 'admin.catalogue_rooms.';

    /**
     * @param CatalogueRoomRepository $catalogueRoomRepos
     */
    public function __construct(CatalogueRoomRepository $catalogueRoomRepos)
    {
        $this->catalogueRoomRepos = $catalogueRoomRepos;
    }

    public function index()
    {
        $hotelID = Auth::user()->org_id;

        $hotel = Hotel::query()->where('id', $hotelID)->firstOrFail();

        $catalogueRooms = CatalogueRoom::query()->with('hotel', 'attributes', 'images')->where('hotel_id', $hotelID)->paginate(10);
        
        if (request()->has('keyword') && !empty(request()->input('keyword'))) {
            $catalogueRooms = CatalogueRoom::query()->with('hotel', 'attributes', 'images')->where('name', request()->input('keyword'))->where('hotel_id', $hotelID)->paginate(10);
        }

        $roomBookedQtyMapBy = $this->catalogueRoomRepos->getRoomBookedQtyToday($hotelID);
        return view(self::PATH_VIEW . __FUNCTION__, compact('catalogueRooms', 'hotel', 'roomBookedQtyMapBy'));
    }


    public function create()
    {
        $attributes = Attribute::all();
        $hotels = Hotel::all();
        return view(self::PATH_VIEW . __FUNCTION__)->with(compact('hotels', 'attributes'));
    }


    public function store(CreateRequest $request)
    {
        $attributes = $request->all()['attributes'] ?? [];

        $data = [
            'name' => $request->name,
            'price' => $request->price,
            'price_hour' => $request->price_hour,
            'status' => $request->status ?? 2,
            'description' => $request->description,
            'hotel_id' => $request->hotel_id,
            "number_adult" => $request->number_adult,
            "number_child" => $request->number_child,
            "acreage" => $request->acreage
        ];

        $listPath = [];

        try {
            DB::beginTransaction();

            $data['thumbnail'] = Storage::put('catalogerooms', $request->thumbnail);

            $catalogueRoom = CatalogueRoom::query()->create($data);

            foreach ($attributes as $attribute) {
                CatalogueRoomAttribute::query()->create([
                    'catalogue_room_id' => $catalogueRoom->id,
                    'attribute_id' => $attribute,
                ]);
            }

            foreach ($request->images as $image) {
                $path = Storage::put('images', $image);

                $listPath[] = $path;

                Image::query()->create([
                    'path' => $path,
                    'object_id' => $catalogueRoom->id
                ]);
            }

            DB::commit();

            return redirect()->route('admin.catalogue-rooms.index')->with('success', 'Thêm loại phòng thành công!');
        } catch (\Exception $e) {

            Storage::delete($data['thumbnail']);

            foreach ($listPath as $pathImg) {
                Storage::delete($pathImg);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $hotelID = Auth::user()->org_id;

        $attributes = Attribute::all();

        $catalogueRoom = CatalogueRoom::query()->with('hotel', 'attributes', 'images')->where('id', $id)->where('hotel_id', $hotelID)->firstOrFail();
        // dd($catalogueRoom->toArray());
        return view(self::PATH_VIEW . __FUNCTION__, compact('catalogueRoom', 'attributes'));
    }


    public function update(UpdateRequest $request, $id)
    {
        // dd($request->all());
        $hotelID = Auth::user()->org_id;

        $catalogueRoom = CatalogueRoom::query()->where('id', $id)->where('hotel_id', $hotelID)->firstOrFail();

        $oldThumbnail = $catalogueRoom->thumbnail;

        $attributes = $request->all()['attributes'] ?? [];

        $data = [
            'name' => $request->name,
            'price' => $request->price,
            'price_hour' => $request->price_hour,
            'status' => $request->status ?? 2,
            'description' => $request->description,
            'hotel_id' => $request->hotel_id,
            "number_adult" => $request->number_adult,
            "number_child" => $request->number_child,
            "acreage" => $request->acreage
        ];

        $listPath = [];

        try {
            DB::beginTransaction();

            if ($request->has('thumbnail')) {
                $data['thumbnail'] = Storage::put('catalogerooms', $request->thumbnail);
            }

            $catalogueRoom->update($data);

            CatalogueRoomAttribute::query()->where('catalogue_room_id', $catalogueRoom->id)->delete();

            foreach ($attributes as $attribute) {
                CatalogueRoomAttribute::query()->create([
                    'catalogue_room_id' => $catalogueRoom->id,
                    'attribute_id' => $attribute,
                ]);
            }

            if ($request->has('images')) {
                foreach ($request->images as $image) {
                    $path = Storage::put('images', $image);
    
                    $listPath[] = $path;
    
                    Image::query()->create([
                        'path' => $path,
                        'object_id' => $catalogueRoom->id
                    ]);
                }
            }

            DB::commit();

            if ($request->has('thumbnail')) {
                Storage::delete($oldThumbnail);
            }

            return redirect()->back()->with('success', 'Sửa loại phòng thành công!');
        } catch (\Exception $e) {

            if ($request->has('thumbnail')) {
                Storage::delete($data['thumbnail']);
            }

            foreach ($listPath as $pathImg) {
                Storage::delete($pathImg);
            }

            return back()->with('error', $e->getMessage());
        }
    }


    public function destroy($id)
    {
        $catalogueRoom = CatalogueRoom::query()->where('id', $id)->firstOrFail();

        try {
            DB::beginTransaction();

            $catalogueRoom->delete();

            CatalogueRoomAttribute::query()->where('catalogue_room_id', $id)->delete();

            $images = Image::query()->where('object_id', $id)->get();

            Image::query()->where('object_id', $id)->delete();
            
            DB::commit();

            Storage::delete($catalogueRoom->thumbnail);

            foreach ($images as $image) {
                Storage::delete($image->path);
            }

            return redirect()->route('admin.catalogue-rooms.index')->with('success', 'Xóa loại phòng thành công!');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function deleteImageMulti(Request $request)
    {
        $data = $request->images_id;

        if (empty($data)) {
            return back()->with('error', 'Bạn chưa chọn ảnh nào cả');
        }

        foreach ($data as $id) {
            $image = Image::query()->where('id', $id)->first();
            if ($image->path && Storage::exists($image->path)) {
                Storage::delete($image->path);
            }
        }
        
        $images = Image::whereIn('id', $data)->delete();

        return back()->with('success', "Bạn đã xóa $images ảnh!");
    }

    public function storeImage(Request $request) {}

}
