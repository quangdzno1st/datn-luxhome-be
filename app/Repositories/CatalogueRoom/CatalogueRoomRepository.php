<?php

namespace App\Repositories\CatalogueRoom;

use App\Constant\Enum\RoomStatusEnum;
use App\Constant\Enum\StatusOrderEnum;
use App\Models\CatalogueRoom;
use App\Models\Order;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CatalogueRoomRepository extends BaseRepository implements CatalogueRoomInterface
{
    public function model(): string
    {
        return CatalogueRoom::class;
    }

    public function existsById($id): bool
    {
        return CatalogueRoom::query()->where('id', $id)->exists();
    }

    public function searchByPage($request)
    {

        $startDate = $request->has('start_date')
            ? Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->format('Y-m-d')
            : Carbon::now()->format('Y-m-d');

        $endDate = $request->has('end_date')
            ? Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->format('Y-m-d')
            : Carbon::tomorrow()->format('Y-m-d');

        $cityId = request()->input('city_id');
        $numberAdult = request()->input('number_adult');
        $numberChild = request()->input('number_child');
//        $categoryIds = $request->has('attribute_value_id')
//            ? DB::table('catalogue_room_attribute')->where('attribute_value_id', $request->attribute_value_id)->pluck('catalogue_room_id')
//            : DB::table('catalogue_room_attribute')->pluck('catalogue_room_id');

        $categoriesQuery = CatalogueRoom::query();
//            ->where('org_id', $request->org_id)
//            ->whereIn('id', $categoryIds);

//        if ($request->has('catalogue_room_id') && !empty($request->catalogue_room_id)) {
//            $categoriesQuery->where('id', $request->catalogue_room_id);
//        }
        if ($numberAdult){
            $categoriesQuery->where('number_adult', $numberAdult);
        }

        if ($numberChild){
            $categoriesQuery->where('number_child', $numberChild);
        }
        $categories = $categoriesQuery->with('rooms')->get();

        $orders = Order::with('orderItem')
            ->where('status','<>', StatusOrderEnum::CHUA_THANH_TOAN->value)
            ->where(function ($query) use ($startDate, $endDate) {
            $query->whereBetween('start_date', [$startDate, $endDate])
                ->orWhereBetween('end_date', [$startDate, $endDate])
                ->orWhere(function ($query) use ($startDate, $endDate) {
                    $query->where('start_date', '<=', $startDate)
                        ->where('end_date', '>=', $endDate);
                });
        })->get();

        $roomIds = $orders->flatMap(function ($order) {

            return $order->orderItem->flatMap(function ($item) {
                return $item->room_id ? [$item->room_id] : [];
            });
        })->unique()->values()->toArray();

        return $categories->map(function ($category) use ($roomIds,$startDate,$endDate,$numberChild,$numberAdult) {
            $filteredRooms = $category->rooms()->whereNotIn('id', $roomIds)
                ->where('rooms.status', RoomStatusEnum::SAN_SANG_SU_DUNG->value)->get();
            return [
                'id' => $category->id,
                'name' => $category->name,
                'number_adult_search' =>$numberAdult ,
                'number_child_search' =>  $numberChild ?? null,
                'number_adult' =>$category->number_adult ,
                'number_child' =>  $category->number_child,
                'hotel_id' => $category->hotel_id,
                'attributeValues' => $category?->attributeValues,
                'images' => $category?->images,
                'org_id' => $category->org_id,
                'price' => $category->price,
                'description' => $category->description,
                'image' => $category->image,
                'view' => $category->view,
                'like' => $category->like,
                'status' => $category->status,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'rooms_count' => $filteredRooms->count(),
                'available_rooms' => $filteredRooms->map(function ($room) {
                    return [
                        'room_id' => $room->id,
                        'code' => $room->code,
                    ];
                })->toArray(),
            ];
        });
    }

    public function getAllByOrgId($orgId)
    {
        return CatalogueRoom::query()->where('org_id', $orgId)
            ->orderBy('name')
            ->get();
    }
}