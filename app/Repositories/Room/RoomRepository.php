<?php

namespace App\Repositories\Room;

use App\Constant\Enum\RoomStatusEnum;
use App\Constant\Enum\StatusOrderEnum;
use App\Models\Order;
use App\Models\Room;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Carbon;

class RoomRepository extends BaseRepository implements RoomInterface
{

    public function model(): string
    {
        return Room::class;
    }

    public function getById(string $id): ?Room
    {
        $query = $this->model::query();
        $query->select("rooms.*", "c.name as catalogue_room_name", "h.name as hotel_name");

        $query->join("catalogue_rooms as c", "rooms.catalogue_room_id", "=", "c.id");
        $query->join("hotels as h", "c.hotel_id", "=", "h.id");

        $query->where("rooms.id", $id);

        return $query->first();
    }

    public function getRoomAvailableByIdInAndOrgId($orgId, $ids, $startDate, $endDate)
    {
        $orders = Order::with('orderItem')->whereIn('status', [StatusOrderEnum::DA_XAC_NHAN->value, StatusOrderEnum::YEU_CAU_HUY])
            ->where('org_id', $orgId)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        $query->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            })->get();

        $roomBookedIds = $orders->flatMap(function ($order) {
            return $order->orderItem->flatMap(function ($item) {
                return [$item->room_id];
            });
        });

        return Room::query()
            ->join("catalogue_rooms as c", "rooms.catalogue_room_id", "=", "c.id")
            ->where("c.hotel_id", "=", $orgId)
            ->whereIn('rooms.status', [RoomStatusEnum::SAN_SANG_SU_DUNG->value, RoomStatusEnum::DANG_DON_DEP])
            ->whereNotIn("rooms.id", $roomBookedIds)
            ->whereIn("rooms.id", $ids)
            ->select("rooms.id", "c.id as catalogue_room_id ", "c.name as catalogue_room_name", "c.price")
            ->get();
    }

    public function getRoomBookedIdByIdIn($orgId, $ids, $startDate, $endDate)
    {
        $orders = Order::with('orderItem')->whereIn('status', [StatusOrderEnum::DA_XAC_NHAN->value, StatusOrderEnum::YEU_CAU_HUY])
            ->where('org_id', $orgId)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        $query->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            })->get();

        return $orders->flatMap(function ($order) use ($ids) {
            return $order->orderItem->filter(function ($item) use ($ids) {
                return in_array($item->room_id, $ids);
            })->pluck('room_id');
        });
    }

    public function getRoomBookedIdToday($orgId)
    {
        $startDate = Carbon::now()->setTime(14, 0);
        $endDate = Carbon::now()->addDay()->setTime(12, 0);

        $orders = Order::with('orderItem')
            ->whereIn('status', [StatusOrderEnum::DA_XAC_NHAN->value, StatusOrderEnum::YEU_CAU_HUY])
            ->where('org_id', $orgId)
            ->where('start_date', '<', $endDate)
            ->where('start_date', '>=', $startDate)
            ->get();

        return $orders->flatMap(function ($order) {

            return $order->orderItem->flatMap(function ($item) {
                return $item->room_id ? [$item->room_id] : [];
            });
        })->unique()->values()->toArray();
    }

}