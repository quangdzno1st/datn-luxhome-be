<?php

namespace App\Services\impl;

use App\Constant\Enum\RoleEnum;
use App\Models\Order;
use App\Models\Rate;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class StatisticalServiceImpl
{
    public function statistical()
    {
        $hotel_id = $this->checkRole();

        $currentYear = Carbon::now()->year;

        $startDate = '';

        $endDate = '';

        $selectTime = '';

        $query = Order::query();

        if (session()->has('handle_data')) {

            $data = session('handle_data');

            // dd($data);

            if (!empty($data['hotel_id'])) {
                $hotel_id = $data['hotel_id'];
            }

            $startDate = $data['start_date'];

            $endDate = $data['end_date'];

            $selectTime = $data['option_time'];

            if ($selectTime == 'quarter') {
                $query->selectRaw('YEAR(created_at) AS year, QUARTER(created_at) AS quarter, SUM(total_amount) AS total_revenue, COUNT(id) AS total_order')
                    ->whereBetween('created_at', [$startDate, $endDate]);

                if (!empty($hotel_id)) {
                    $query->where('org_id', $hotel_id);
                }

                $query->groupBy('year', 'quarter')
                    ->orderBy('year')
                    ->orderBy('quarter');
            }
            if ($selectTime == 'year') {
                $query->selectRaw('YEAR(created_at) AS year, SUM(total_amount) AS total_revenue, COUNT(id) AS total_order')
                    ->whereYear('created_at', '>=', $startDate)
                    ->whereYear('created_at', '<=', $endDate);

                if (!empty($hotel_id)) {
                    $query->where('org_id', $hotel_id);
                }

                $query->groupBy('year')
                    ->orderBy('year');
            }
            if ($selectTime == 'month') {
                $query->selectRaw('YEAR(created_at) as year, MONTH(created_at) AS month, SUM(total_amount) AS total_revenue, COUNT(id) AS total_order')
                    ->where('created_at', '>=', $startDate)
                    ->where('created_at', '<=', $endDate);

                if (!empty($hotel_id)) {
                    $query->where('org_id', $hotel_id);
                }

                $query->groupBy('year', 'month')
                    ->orderBy('year')
                    ->orderBy('month');
            }
        } else {
            $query->selectRaw('YEAR(created_at) AS year, MONTH(created_at) AS month, SUM(total_amount) AS total_revenue, COUNT(id) AS total_order')
                ->whereYear('created_at', $currentYear);

            if (!empty($hotel_id)) {
                $query->where('org_id', $hotel_id);
            }

            $query->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month');
        }

        session()->remove('handle_data');

        $data = $query->get();

        $arrayData = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'select_time' => $selectTime,
            'data_statistical' => $data,
            'hotel_id' => $hotel_id
        ];
        return $arrayData;
    }

    public function totalOrder()
    {
        $hotel_id = $this->checkRole();

        $query = Order::query();

        $query->selectRaw('COUNT(id) AS total_order');

        if (!empty($hotel_id)) {
            $query->where('org_id', $hotel_id);
        }

        $totalOrder = $query->first();

        return $totalOrder;
        
    }

    public function totalRevenue()
    {
        $hotel_id = $this->checkRole();

        $query = Order::query();

        $query->selectRaw('SUM(total_amount) AS total_revenue');

        if (!empty($hotel_id)) {
            $query->where('org_id', $hotel_id);
        }

        $totalOrder = $query->first();

        return $totalOrder;
    }

    public function totalUser()
    {
        $totalUser = User::count();

        return $totalUser;
    }

    public function totalRating()
    {
        $hotel_id = $this->checkRole();

        $query = Rate::query();

        $query->selectRaw('COUNT(id) AS total_rating');

        if (!empty($hotel_id)) {
            $query->where('org_id', $hotel_id);
        }

        $totalRating = $query->first();

        return $totalRating;
    }

    public function checkRole()
    {
        $hotel_id = '';
        if (Auth::check() && Auth::user()->type == RoleEnum::Admin->value) {
            $hotel_id = Auth::user()->org_id;
        }
        return $hotel_id;
    }
}