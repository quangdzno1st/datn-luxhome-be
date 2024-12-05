<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\statistical\AdminStatisticalRequest;
use App\Models\Hotel;
use App\Services\impl\StatisticalServiceImpl;

class StatisticalController extends Controller
{
    protected $statistical;

    public function __construct(StatisticalServiceImpl $statistical)
    {
        $this->statistical = $statistical;
    }

    public function index()
    {
        $data = $this->statistical->statistical();

        $optionTime = [
            'month' => 'Tháng',
            'quarter' => 'Quý',
            'year' => 'Năm'
        ];

        $startDate = $data['start_date'];

        $endDate = $data['end_date'];

        $selectTime = $data['select_time'];

        $hotel_id = $data['hotel_id'];

        $hotels = Hotel::all();

        $thongke = $data['data_statistical'];

        $totalOrder = $this->statistical->totalOrder();

        $totalUser = $this->statistical->totalUser();

        $totalRating = $this->statistical->totalRating();

        $totalRevenue = $this->statistical->totalRevenue();

        $thongkeOrderByStatus = $data['orders'];

        // dd($thongkeOrderByStatus->toArray());

        $thongkeTotalRoom = $data['total_room'];

        $thongkeTotalRoomBeingBooked = $data['total_room_being_booked'];

        // dd($thongke->toArray());
        session()->remove('handle_data');

        return view('admin.statisticals.index', compact('thongkeTotalRoom', 'thongkeTotalRoomBeingBooked', 'thongkeOrderByStatus','totalRevenue', 'totalOrder', 'totalRating', 'totalUser', 'thongke', 'hotels', 'hotel_id', 'optionTime', 'startDate', 'endDate', 'selectTime'));
    }

    public function handleStatistical()
    {
        session()->put('handle_data', request()->except('_token'));

        return back();
    }
}