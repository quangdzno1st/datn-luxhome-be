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
        $validator = Validator::make($request->all(), [
            'org_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $startDate = $request->input('start_date', Carbon::now()->startOfDay());
        $endDate = $request->input('end_date', Carbon::tomorrow()->endOfDay());
        $categoryIds = $request->has('attribute_value_id')
            ? DB::table('catalogue_room_attribute')->where('attribute_value_id', $request->attribute_value_id)->pluck('catalogue_room_id')
            : DB::table('catalogue_room_attribute')->pluck('catalogue_room_id');

        $categoriesQuery = CatalogueRoom::query()->where('org_id', $request->org_id)
            ->whereIn('id', $categoryIds);

        if ($request->has('catalogue_room_id') && !empty($request->catalogue_room_id)) {
            $categoriesQuery->where('id', $request->catalogue_room_id);
        }
        $categories = $categoriesQuery->with('rooms')->get();


        $orders = Order::with('orderItem')->where('status','<>', StatusOrderEnum::CHUA_THANH_TOAN->value)
            ->where(function ($query) use ($startDate, $endDate) {
            $query->whereBetween('start_date', [$startDate, $endDate])
                ->orWhereBetween('end_date', [$startDate, $endDate])
                ->orWhere(function ($query) use ($startDate, $endDate) {
                    $query->where('start_date', '<=', $startDate)
                        ->where('end_date', '>=', $endDate);
                });
        })->get();

        $roomCodes = $orders->flatMap(function ($order) {
            return $order->orderItem->flatMap(function ($item) {
                return json_decode($item->room_codes, true);
            });
        })->unique()->values()->toArray();

        $roomsCount = $categories->map(function ($category) use ($roomCodes) {
            $filteredRooms = $category->rooms()->whereNotIn('id', $roomCodes)
                ->where('rooms.status', RoomStatusEnum::SAN_SANG_SU_DUNG->value)->get();
            return [
                'id' => $category->id,
                'name' => $category->name,
                'org_id' => $category->org_id,
                'price' => $category->price,
                'description' => $category->description,
                'image' => $category->image,
                'view' => $category->view,
                'like' => $category->like,
                'status' => $category->status,
                'rooms_count' => $filteredRooms->count(),
                'available_rooms' => $filteredRooms->map(function ($room) {
                    return ['room_id' => $room->id];
                })->toArray(),
            ];
        });

        return $roomsCount;
    }

    public function getAllByOrgId($orgId)
    {
        return CatalogueRoom::query()->where('org_id', $orgId)
            ->orderBy('name')
            ->get();
    }
}