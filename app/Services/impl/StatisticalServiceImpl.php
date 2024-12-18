<?php

namespace App\Services\impl;

use App\Models\Rate;
use App\Models\Room;
use App\Models\User;
use App\Models\Order;
use App\Models\CatalogueRoom;
use Illuminate\Support\Carbon;
use App\Constant\Enum\RoleEnum;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Constant\Enum\StatusOrderEnum;

class StatisticalServiceImpl
{
    public function statistical()
    {
        $hotel_id = $this->checkRole();

        $currentYear = Carbon::now()->year;

        // Ngày đầu năm
        $startOfYear = Carbon::now()->startOfYear(); // 2024-01-01 00:00:00

        // Ngày cuối năm
        $endOfYear = Carbon::now()->endOfYear(); // 2024-12-31 23:59:59

        $startDate = Carbon::now()->startOfYear()->format('Y-m-d');

        $endDate = Carbon::now()->endOfYear()->format('Y-m-d');

        $selectTime = '';

        $query = Order::query()->where('net_amount', '!=', null);

        if (session()->has('handle_data')) {

            $data = session('handle_data');

            if (!empty($data['hotel_id'])) {
                $hotel_id = $data['hotel_id'];
            }

            $startDate = $data['start_date'];

            $endDate = $data['end_date'];

            $selectTime = $data['option_time'];

            // Tìm theo quý
            if ($selectTime == 'quarter') {
                $query->selectRaw('YEAR(created_at) AS year, QUARTER(created_at) AS quarter, SUM(net_amount) AS total_revenue, COUNT(id) AS total_order');
                if (empty($startDate) && !empty($endDate)) {
                    $query->whereBetween(DB::raw('QUARTER(created_at)'), [$this->quarter($startOfYear), $this->quarter($endDate)]);
                } elseif (!empty($startDate) && empty($endDate)) {
                    $query->whereBetween(DB::raw('QUARTER(created_at)'), [$this->quarter($startDate), $this->quarter($endOfYear)]);
                } elseif (empty($startDate) && empty($endDate)) {
                    $query->whereYear('created_at', $currentYear);
                } else {
                    $query->whereBetween(DB::raw('QUARTER(created_at)'), [$this->quarter($startDate), $this->quarter($endDate)]);
                }

                if (!empty($hotel_id)) {
                    $query->where('org_id', $hotel_id);
                }

                $query->groupBy('year', 'quarter')
                    ->orderBy('year')
                    ->orderBy('quarter');
            }

            // Tìm theo năm
            if ($selectTime == 'year') {
                $query->selectRaw('YEAR(created_at) AS year, SUM(net_amount) AS total_revenue, COUNT(id) AS total_order');
                if (empty($startDate) && !empty($endDate)) {
                    $query->whereYear('created_at', $endDate);
                } elseif (!empty($startDate) && empty($endDate)) {
                    $query->whereYear('created_at', $startDate);
                } elseif (empty($startDate) && empty($endDate)) {
                    $query->whereYear('created_at', $currentYear);
                } else {
                    $query->whereYear('created_at', '>=', $startDate)
                        ->whereYear('created_at', '<=', $endDate);
                }

                if (!empty($hotel_id)) {
                    $query->where('org_id', $hotel_id);
                }

                $query->groupBy('year')
                    ->orderBy('year');
            }

            // Tìm theo tháng
            if ($selectTime == 'month') {
                $query->selectRaw('YEAR(created_at) as year, MONTH(created_at) AS month, SUM(net_amount) AS total_revenue, COUNT(id) AS total_order');
                if (empty($startDate) && !empty($endDate)) {
                    $query->whereBetween(DB::raw('MONTH(created_at)'), [$this->month($startOfYear), $this->month($endDate)]);
                } elseif (!empty($startDate) && empty($endDate)) {
                    $query->whereBetween(DB::raw('MONTH(created_at)'), [$this->month($startDate), $this->month($endOfYear)]);
                } elseif (empty($startDate) && empty($endDate)) {
                    $query->whereYear('created_at', $currentYear);
                } else {
                    $query->whereBetween(DB::raw('MONTH(created_at)'), [$this->month($startDate), $this->month($endDate)]);
                }

                if (!empty($hotel_id)) {
                    $query->where('org_id', $hotel_id);
                }

                $query->groupBy('year', 'month')
                    ->orderBy('year')
                    ->orderBy('month');
            }
        } else {

            // Mặc định
            $query->selectRaw('YEAR(created_at) AS year, MONTH(created_at) AS month, SUM(net_amount) AS total_revenue, COUNT(id) AS total_order')
                ->whereYear('created_at', $currentYear);

            if (!empty($hotel_id)) {
                $query->where('org_id', $hotel_id);
            }

            $query->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month');
        }

        // session()->remove('handle_data');

        $data = $query->get();

        $orders = $this->thongKeOrderByStatus();
        // dd($orders->toArray());

        $arrayData = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'select_time' => $selectTime,
            'data_statistical' => $data,
            'hotel_id' => $hotel_id,
            'orders' => $orders,
            'total_room' => $this->totalRoom(),
            'total_room_being_booked' => $this->totalRoomBeingBooked(),
        ];

        return $arrayData;
    }

    public function totalOrder()
    {
        $hotel_id = $this->checkRole();

        $currentYear = Carbon::now()->year;

        // Ngày đầu năm
        $startOfYear = Carbon::now()->startOfYear(); // 2024-01-01 00:00:00

        // Ngày cuối năm
        $endOfYear = Carbon::now()->endOfYear(); // 2024-12-31 23:59:59

        $query = Order::query();

        if (session()->has('handle_data')) {

            $data = session('handle_data');

            if (!empty($data['hotel_id'])) {
                $hotel_id = $data['hotel_id'];
            }

            $startDate = $data['start_date'];

            $endDate = $data['end_date'];

            $selectTime = $data['option_time'];

            if ($selectTime == 'quarter') {
                $query->selectRaw('COUNT(id) AS total_order');
                if (empty($startDate) && !empty($endDate)) {
                    $query->whereBetween(DB::raw('QUARTER(created_at)'), [$this->quarter($startOfYear), $this->quarter($endDate)]);
                } elseif (!empty($startDate) && empty($endDate)) {
                    $query->whereBetween(DB::raw('QUARTER(created_at)'), [$this->quarter($startDate), $this->quarter($endOfYear)]);
                } elseif (empty($startDate) && empty($endDate)) {
                    $query->whereYear('created_at', $currentYear);
                } else {
                    $query->whereBetween(DB::raw('QUARTER(created_at)'), [$this->quarter($startDate), $this->quarter($endDate)]);
                }

                if (!empty($hotel_id)) {
                    $query->where('org_id', $hotel_id);
                }
            }
            if ($selectTime == 'year') {
                $query->selectRaw('COUNT(id) AS total_order');
                if (empty($startDate) && !empty($endDate)) {
                    $query->whereYear('created_at', $endDate);
                } elseif (!empty($startDate) && empty($endDate)) {
                    $query->whereYear('created_at', $startDate);
                } elseif (empty($startDate) && empty($endDate)) {
                    $query->whereYear('created_at', $currentYear);
                } else {
                    $query->whereYear('created_at', '>=', $startDate)
                        ->whereYear('created_at', '<=', $endDate);
                }

                if (!empty($hotel_id)) {
                    $query->where('org_id', $hotel_id);
                }
            }
            if ($selectTime == 'month') {
                $query->selectRaw('COUNT(id) AS total_order');
                if (empty($startDate) && !empty($endDate)) {
                    $query->whereBetween(DB::raw('MONTH(created_at)'), [$this->month($startOfYear), $this->month($endDate)]);
                } elseif (!empty($startDate) && empty($endDate)) {
                    $query->whereBetween(DB::raw('MONTH(created_at)'), [$this->month($startDate), $this->month($endOfYear)]);
                } elseif (empty($startDate) && empty($endDate)) {
                    $query->whereYear('created_at', $currentYear);
                } else {
                    $query->whereBetween(DB::raw('MONTH(created_at)'), [$this->month($startDate), $this->month($endDate)]);
                }

                if (!empty($hotel_id)) {
                    $query->where('org_id', $hotel_id);
                }
            }
        } else {
            $query->selectRaw('COUNT(id) AS total_order');

            if (!empty($hotel_id)) {
                $query->where('org_id', $hotel_id);
            }
        }

        $totalOrder = $query->first();

        return $totalOrder;
    }

    public function totalRevenue()
    {
        $hotel_id = $this->checkRole();

        $currentYear = Carbon::now()->year;

        // Ngày đầu năm
        $startOfYear = Carbon::now()->startOfYear(); // 2024-01-01 00:00:00

        // Ngày cuối năm
        $endOfYear = Carbon::now()->endOfYear(); // 2024-12-31 23:59:59

        $query = Order::query()->where('net_amount', '!=', null);

        if (session()->has('handle_data')) {

            $data = session('handle_data');

            if (!empty($data['hotel_id'])) {
                $hotel_id = $data['hotel_id'];
            }

            $startDate = $data['start_date'];

            $endDate = $data['end_date'];

            $selectTime = $data['option_time'];

            if ($selectTime == 'quarter') {
                $query->selectRaw('SUM(net_amount) AS total_revenue');
                if (empty($startDate) && !empty($endDate)) {
                    $query->whereBetween(DB::raw('QUARTER(created_at)'), [$this->quarter($startOfYear), $this->quarter($endDate)]);
                } elseif (!empty($startDate) && empty($endDate)) {
                    $query->whereBetween(DB::raw('QUARTER(created_at)'), [$this->quarter($startDate), $this->quarter($endOfYear)]);
                } elseif (empty($startDate) && empty($endDate)) {
                    $query->whereYear('created_at', $currentYear);
                } else {
                    $query->whereBetween(DB::raw('QUARTER(created_at)'), [$this->quarter($startDate), $this->quarter($endDate)]);
                }

                if (!empty($hotel_id)) {
                    $query->where('org_id', $hotel_id);
                }
            }
            if ($selectTime == 'year') {
                $query->selectRaw('SUM(net_amount) AS total_revenue');
                if (empty($startDate) && !empty($endDate)) {
                    $query->whereYear('created_at', $endDate);
                } elseif (!empty($startDate) && empty($endDate)) {
                    $query->whereYear('created_at', $startDate);
                } elseif (empty($startDate) && empty($endDate)) {
                    $query->whereYear('created_at', $currentYear);
                } else {
                    $query->whereYear('created_at', '>=', $startDate)
                        ->whereYear('created_at', '<=', $endDate);
                }

                if (!empty($hotel_id)) {
                    $query->where('org_id', $hotel_id);
                }
            }
            if ($selectTime == 'month') {
                $query->selectRaw('SUM(net_amount) AS total_revenue');
                if (empty($startDate) && !empty($endDate)) {
                    $query->whereBetween(DB::raw('MONTH(created_at)'), [$this->month($startOfYear), $this->month($endDate)]);
                } elseif (!empty($startDate) && empty($endDate)) {
                    $query->whereBetween(DB::raw('MONTH(created_at)'), [$this->month($startDate), $this->month($endOfYear)]);
                } elseif (empty($startDate) && empty($endDate)) {
                    $query->whereYear('created_at', $currentYear);
                } else {
                    $query->whereBetween(DB::raw('MONTH(created_at)'), [$this->month($startDate), $this->month($endDate)]);
                }

                if (!empty($hotel_id)) {
                    $query->where('org_id', $hotel_id);
                }
            }
        } else {
            $query->selectRaw('SUM(net_amount) AS total_revenue');

            if (!empty($hotel_id)) {
                $query->where('org_id', $hotel_id);
            }
        }


        $totalOrder = $query->first();

        return $totalOrder;
    }

    public function totalUser()
    {
        $totalUser = User::where('type', User::CUSTOMER)->count();

        return $totalUser;
    }

    public function totalRating()
    {
        $hotel_id = $this->checkRole();

        if (session()->has('handle_data')) {

            $data = session('handle_data');

            if (!empty($data['hotel_id'])) {
                $hotel_id = $data['hotel_id'];
            }
        }

        $query = Rate::query();

        $query->selectRaw('COUNT(id) AS total_rating');

        if (!empty($hotel_id)) {
            $query->where('hotel_id', $hotel_id);
        }

        $totalRating = $query->first();

        return $totalRating;
    }

    public function thongKeOrderByStatus()
    {
        $hotel_id = $this->checkRole();

        $currentYear = Carbon::now()->year;

        // Ngày đầu năm
        $startOfYear = Carbon::now()->startOfYear(); // 2024-01-01 00:00:00

        // Ngày cuối năm
        $endOfYear = Carbon::now()->endOfYear(); // 2024-12-31 23:59:59

        $startDate = '';

        $endDate = '';

        $selectTime = '';

        $query = Order::query();

        if (session()->has('handle_data')) {

            $data = session('handle_data');

            if (!empty($data['hotel_id'])) {
                $hotel_id = $data['hotel_id'];
            }

            $startDate = $data['start_date'];

            $endDate = $data['end_date'];

            $selectTime = $data['option_time'];

            // Tìm theo quý
            if ($selectTime == 'quarter') {
                if (empty($startDate) && !empty($endDate)) {
                    $query->whereBetween(DB::raw('QUARTER(created_at)'), [$this->quarter($startOfYear), $this->quarter($endDate)]);
                } elseif (!empty($startDate) && empty($endDate)) {
                    $query->whereBetween(DB::raw('QUARTER(created_at)'), [$this->quarter($startDate), $this->quarter($endOfYear)]);
                } elseif (empty($startDate) && empty($endDate)) {
                    $query->whereYear('created_at', $currentYear);
                } else {
                    $query->whereBetween(DB::raw('QUARTER(created_at)'), [$this->quarter($startDate), $this->quarter($endDate)]);
                }

                if (!empty($hotel_id)) {
                    $query->where('org_id', $hotel_id);
                }
            }

            // Tìm theo năm
            if ($selectTime == 'year') {
                if (empty($startDate) && !empty($endDate)) {
                    $query->whereYear('created_at', $endDate);
                } elseif (!empty($startDate) && empty($endDate)) {
                    $query->whereYear('created_at', $startDate);
                } elseif (empty($startDate) && empty($endDate)) {
                    $query->whereYear('created_at', $currentYear);
                } else {
                    $query->whereYear('created_at', '>=', $startDate)
                        ->whereYear('created_at', '<=', $endDate);
                }

                if (!empty($hotel_id)) {
                    $query->where('org_id', $hotel_id);
                }
            }

            // Tìm theo tháng
            if ($selectTime == 'month') {
                if (empty($startDate) && !empty($endDate)) {
                    $query->whereBetween(DB::raw('MONTH(created_at)'), [$this->month($startOfYear), $this->month($endDate)]);
                } elseif (!empty($startDate) && empty($endDate)) {
                    $query->whereBetween(DB::raw('MONTH(created_at)'), [$this->month($startDate), $this->month($endOfYear)]);
                } elseif (empty($startDate) && empty($endDate)) {
                    $query->whereYear('created_at', $currentYear);
                } else {
                    $query->whereBetween(DB::raw('MONTH(created_at)'), [$this->month($startDate), $this->month($endDate)]);
                }

                if (!empty($hotel_id)) {
                    $query->where('org_id', $hotel_id);
                }
            }
        } else {
            // Mặc định
            $query->whereYear('created_at', $currentYear);

            if (!empty($hotel_id)) {
                $query->where('org_id', $hotel_id);
            }
        }

        $orders = $query->selectRaw('status, COUNT(status) as quantity_order')->groupBy('status')->orderBy('status')->get();

        return $orders;
    }

    public function totalRoom()
    {
        $hotel_id = $this->checkRole();

        if (session()->has('handle_data')) {

            $data = session('handle_data');

            if (!empty($data['hotel_id'])) {
                $hotel_id = $data['hotel_id'];
            }
        }

        $query = Room::query();

        if (!empty($hotel_id)) {
            $totalRoom = $query->where('hotel_id', $hotel_id)->count();
        } else {
            $totalRoom = $query->count();
        }

        // dd($totalRoom->toArray());
        return $totalRoom;
    }

    public function totalRoomBeingBooked()
    {
        $hotel_id = $this->checkRole();

        $startDate = Carbon::now()->setTime(14, 0);
        $endDate = Carbon::now()->addDay()->setTime(12, 0);

        if (session()->has('handle_data')) {

            $data = session('handle_data');

            if (!empty($data['hotel_id'])) {
                $hotel_id = $data['hotel_id'];
            }
        }

        if (!empty($hotel_id)) {
            $bookedRoomIds = Order::query()
                ->join('order_items as ot', 'ot.order_id', '=', 'orders.id')
                ->join('rooms as r', 'r.id', '=', 'ot.room_id')
                ->whereIn('orders.status', [StatusOrderEnum::DA_XAC_NHAN->value, StatusOrderEnum::YEU_CAU_HUY])
                ->where('orders.org_id', $hotel_id)
                ->where('orders.start_date', '<', $endDate)
                ->where('orders.start_date', '>=', $startDate)
                ->select('ot.room_id');

            $bookedRooms = CatalogueRoom::query()
                ->leftJoin('rooms as r', 'r.catalogue_room_id', '=', 'catalogue_rooms.id')
                ->leftJoinSub(
                    $bookedRoomIds,
                    'booked_rooms',
                    function ($join) {
                        $join->on('r.id', '=', 'booked_rooms.room_id');
                    }
                )->where('catalogue_rooms.hotel_id', $hotel_id)
                ->select(DB::raw('sum(IF(booked_rooms.room_id is null, 0, 1)) as booked_room_qty'))
                ->get()
                ->toArray();
            // dd($bookedRooms);
            return $bookedRooms;
        }

        $bookedRoomIds = Order::query()
            ->join('order_items as ot', 'ot.order_id', '=', 'orders.id')
            ->join('rooms as r', 'r.id', '=', 'ot.room_id')
            ->whereIn('orders.status', [StatusOrderEnum::DA_XAC_NHAN->value, StatusOrderEnum::YEU_CAU_HUY])
            ->where('orders.start_date', '<', $endDate)
            ->where('orders.start_date', '>=', $startDate)
            ->select('ot.room_id');

        $bookedRooms = CatalogueRoom::query()
            ->leftJoin('rooms as r', 'r.catalogue_room_id', '=', 'catalogue_rooms.id')
            ->leftJoinSub(
                $bookedRoomIds,
                'booked_rooms',
                function ($join) {
                    $join->on('r.id', '=', 'booked_rooms.room_id');
                }
            )->select(DB::raw('sum(IF(booked_rooms.room_id is null, 0, 1)) as booked_room_qty'))
            ->get()
            ->toArray();
        // dd($bookedRooms);

        return $bookedRooms;

        // $query = Order::query();

        // if (!empty($hotel_id)) {
        //     $totalRoomBeingBooked = $query->where('org_id', $hotel_id)->whereIn('status', [1, 2])->count();
        // } else {
        //     $totalRoomBeingBooked = $query->whereIn('status', [1, 2])->count();
        // }

        // dd($totalRoomBeingBooked);
        // return $totalRoomBeingBooked;
    }

    public function checkRole()
    {
        $hotel_id = '';
        if (Auth::check() && Auth::user()->type == RoleEnum::SupperAdmin->value) {
            return $hotel_id;
        }
        $hotel_id = Auth::user()->org_id;
        return $hotel_id;
    }

    public function quarter($date)
    {
        $quarter = Carbon::parse($date)->quarter;
        return $quarter;
    }

    public function month($date)
    {
        $month = Carbon::parse($date)->month;
        return $month;
    }
}
