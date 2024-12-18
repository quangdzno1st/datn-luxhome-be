<?php

namespace App\Repositories\CatalogueRoom;

use App\Constant\Enum\RoomStatusEnum;
use App\Constant\Enum\StatusOrderEnum;
use App\Models\CatalogueRoom;
use App\Models\Order;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class   CatalogueRoomRepository extends BaseRepository implements CatalogueRoomInterface
{
    public function model(): string
    {
        return CatalogueRoom::class;
    }

    public function existsById($id): bool
    {
        return CatalogueRoom::query()->where('id', $id)->exists();
    }

    public function searchByPage($request, $hotelId = null)
    {
        $now = Carbon::now();
        $startDateSearch = $request->has('start_date')
            ? Carbon::createFromFormat('Y-m-d', $request->input('start_date'))
            : $now;

        $endDateSearch = $request->has('end_date')
            ? Carbon::createFromFormat('Y-m-d', $request->input('end_date'))
            : $now->addDay();

        $startDateSearch = $startDateSearch->setTime(14, 0);
        $endDateSearch = $endDateSearch->setTime(12, 0);

        $startDate = $startDateSearch->format('Y-m-d H:i');
        $endDate = $endDateSearch->format('Y-m-d H:i');

        //        $startDate = $request->has('start_date')
        //            ? Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->format('Y-m-d')
        //            : Carbon::now()->format('Y-m-d');
        //
        //        $endDate = $request->has('end_date')
        //            ? Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->format('Y-m-d')
        //            : Carbon::tomorrow()->format('Y-m-d');

        $cityId = request()->input('city_id');
        $numberAdult = request()->input('number_adult');
        $numberChild = request()->input('number_child');
        //        $categoryIds = $request->has('attribute_value_id')
        //            ? DB::table('catalogue_room_attribute')->where('attribute_value_id', $request->attribute_value_id)->pluck('catalogue_room_id')
        //            : DB::table('catalogue_room_attribute')->pluck('catalogue_room_id');

        $categoriesQuery = CatalogueRoom::query()->where('status',1);
        //            ->where('org_id', $request->org_id)
        //            ->whereIn('id', $categoryIds);

        //        if ($request->has('catalogue_room_id') && !empty($request->catalogue_room_id)) {
        //            $categoriesQuery->where('id', $request->catalogue_room_id);
        //        }
        if ($numberAdult) {
            $categoriesQuery->where('number_adult', $numberAdult);
        }

        if ($numberChild) {
            $categoriesQuery->where('number_child', $numberChild);
        }
        $categories = $categoriesQuery->with('rooms', 'hotel')
            ->get();

        $orders = Order::with('orderItem')
            ->whereIn('status', [StatusOrderEnum::DA_XAC_NHAN->value, StatusOrderEnum::YEU_CAU_HUY])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        $query->where('start_date', '<=', $endDate)
                            ->where('end_date', '>=', $startDate);
                    });
            })->get();


        $roomIds = $orders->flatMap(function ($order) {

            return $order->orderItem->flatMap(function ($item) {
                return $item->room_id ? [$item->room_id] : [];
            });
        })->unique()->values()->toArray();

        return $categories->map(function ($category) use ($roomIds, $startDate, $endDate, $numberChild, $numberAdult) {
            $filteredRooms = $category->rooms()->whereNotIn('id', $roomIds)
                ->whereIn('rooms.status', [RoomStatusEnum::SAN_SANG_SU_DUNG->value, RoomStatusEnum::DANG_DON_DEP])->get();
            $attributes = $category->attributes;
            return [
                'id' => $category->id,
                'name' => $category->name,
                'number_adult_search' => $numberAdult,
                'number_child_search' => $numberChild ?? null,
                'number_adult' => $category->number_adult,
                'number_child' => $category->number_child,
                'hotel_id' => $category->hotel_id,
                'hotel_name' => $category?->hotel?->name,
                'attributes' => $attributes,
                'images' => $category?->images,
                'org_id' => $category->org_id,
                'price' => $category->price,
                'description' => $category->description,
                'image' => $category->image,
                'view' => $category->view,
                'like' => $category->like,
                'status' => $category->status,
                'start_date' => Carbon::parse($startDate)->format('Y-m-d'),
                'end_date' => Carbon::parse($endDate)->format('Y-m-d'),
                'rooms_count' => $filteredRooms->count(),
                'available_rooms' => $filteredRooms->map(function ($room) {
                    return [
                        'room_id' => $room->id,
                        'code' => $room->code,
                        'hotel_id' => $room->hotel_id
                    ];
                })->toArray(),
            ];
        });
    }

    public function getAllByOrgId($orgId)
    {
        return CatalogueRoom::query()->where('hotel_id', $orgId)
            ->orderBy('name')
            ->get();
    }

    public function getByOrderId($orderId)
    {
        $query = CatalogueRoom::query()
            ->select(
                'catalogue_rooms.id',
                'catalogue_rooms.name',
                'catalogue_rooms.price',
                DB::raw('GROUP_CONCAT(r.code SEPARATOR ", ") as room_names'),
                DB::raw('COUNT(r.id) as total_rooms'),
                DB::raw('COUNT(r.id) * catalogue_rooms.price as total_price')
            )
            ->join('rooms as r', 'r.catalogue_room_id', '=', 'catalogue_rooms.id')
            ->join('order_items as ot', 'ot.room_id', '=', 'r.id')
            ->where('ot.order_id', $orderId)
            ->groupBy('catalogue_rooms.id')
            ->orderBy('catalogue_rooms.name');

        return $query->get();
    }

    public function getRoomBookedQtyToday($orgId)
    {
        $startDate = Carbon::now()->setTime(14, 0);
        $endDate = Carbon::now()->addDay()->setTime(12, 0);

        if (!empty($orderId)) {
            $bookedRoomIds = Order::query()
                ->join('order_items as ot', 'ot.order_id', '=', 'orders.id')
                ->join('rooms as r', 'r.id', '=', 'ot.room_id')
                ->whereIn('orders.status', [StatusOrderEnum::DA_XAC_NHAN->value, StatusOrderEnum::YEU_CAU_HUY])
                ->where('orders.org_id', $orgId)
                ->where('orders.start_date', '<', $endDate)
                ->where('orders.start_date', '>=', $startDate)
                ->select('ot.room_id');

            return CatalogueRoom::query()
                ->leftJoin('rooms as r', 'r.catalogue_room_id', '=', 'catalogue_rooms.id')
                ->leftJoinSub(
                    $bookedRoomIds,
                    'booked_rooms',
                    function ($join) {
                        $join->on('r.id', '=', 'booked_rooms.room_id');
                    }
                )
                ->where('catalogue_rooms.hotel_id', $orgId)
                ->groupBy('catalogue_rooms.id')
                ->select('catalogue_rooms.id', DB::raw('count(r.id) as total_rooms, sum(IF(booked_rooms.room_id is null, 0, 1)) as booked_room_qty'))
                ->get()
                ->keyBy('id')
                ->toArray();
        }

        $bookedRoomIds = Order::query()
            ->join('order_items as ot', 'ot.order_id', '=', 'orders.id')
            ->join('rooms as r', 'r.id', '=', 'ot.room_id')
            ->whereIn('orders.status', [StatusOrderEnum::DA_XAC_NHAN->value, StatusOrderEnum::YEU_CAU_HUY])
            ->where('orders.start_date', '<', $endDate)
            ->where('orders.start_date', '>=', $startDate)
            ->select('ot.room_id');

        return CatalogueRoom::query()
            ->leftJoin('rooms as r', 'r.catalogue_room_id', '=', 'catalogue_rooms.id')
            ->leftJoinSub(
                $bookedRoomIds,
                'booked_rooms',
                function ($join) {
                    $join->on('r.id', '=', 'booked_rooms.room_id');
                }
            )
            ->groupBy('catalogue_rooms.id')
            ->select('catalogue_rooms.id', DB::raw('count(r.id) as total_rooms, sum(IF(booked_rooms.room_id is null, 0, 1)) as booked_room_qty'))
            ->get()
            ->keyBy('id')
            ->toArray();
    }
}
