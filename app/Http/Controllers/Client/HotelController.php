<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Models\Hotel;
use App\Models\Rate;
use App\Repositories\CatalogueRoom\CatalogueRoomRepository;
use App\Repositories\Hotel\HotelRepository;
use Illuminate\Http\Request;


class HotelController extends Controller
{
    private $catalogueRoomRepository;
    private $hotelRoomRepository;

    public function __construct(
        CatalogueRoomRepository $catalogueRoomRepository,
        HotelRepository         $hotelRoomRepository,
    )
    {
        $this->catalogueRoomRepository = $catalogueRoomRepository;
        $this->hotelRoomRepository = $hotelRoomRepository;
    }

    public function show(Request $request, $id)
    {
        $hotel = Hotel::query()->where('status',1)->findOrFail($id);

        $view = $hotel->view;
        $hotel->update(['view' => $view + 1]);
        $searchData = !$request->check ? session('search_data') : $this->catalogueRoomRepository->searchByPage($request, $hotel['id']);
        $rates = Rate::query()->where('hotel_id', $id)->paginate(20);
        session(['search_data' => $searchData]);
        session(['hotel_id' => $hotel->id]);
        session(['search_data' => $searchData]);
        session([
            'start_date' => $request->start_date ?? session('start_date'),
            'end_date' => $request->end_date ?? session('end_date'),
        ]);

        $filteredData = collect($searchData)->filter(function ($item) use ($hotel) {
            return $item['hotel_id'] === $hotel->id;
        });
//        dd($filteredData);
        return view('client.hotel', compact('filteredData', 'hotel', 'rates'));
    }

    public function search(SearchRequest $request)
    {
        $data = $this->catalogueRoomRepository->searchByPage($request);
        session(['search_data' => $data]);
        session(['start_date' => $request->start_date]);
        session(['end_date' => $request->end_date]);

        if (isset($request->check) && $request->check) {
            return redirect()->route('hotel.show', ['hotel_id' => $request->hotel_id]);
        }

        $prices = $request->price ?? null;
        $stars = $request->star ?? null;
        $minValue = PHP_INT_MAX;
        $maxValue = PHP_INT_MIN;

        if ($prices && is_array($prices)) {
            foreach ($prices as $ranges) {
                if (strpos($ranges, '-') !== false) {
                    list($min, $max) = explode('-', $ranges);
                    $minValue = min($minValue, (int)$min);
                    $maxValue = max($maxValue, (int)$max);
                } else {
                    $minValue = min($minValue, (int)$ranges);
                }
            }
        }

        $hotelIds = $data
            ->filter(function ($hotel) {
                return $hotel['rooms_count'] > 0;
            })
            ->pluck('hotel_id');

        $query = Hotel::query()
            ->whereIn('id', $hotelIds)
            ->where('city_id', $request->city_id);

        if ($minValue !== PHP_INT_MAX || $maxValue !== PHP_INT_MIN) {
            $query->whereHas('catalogues', function ($query) use ($minValue, $maxValue) {
                if ($minValue !== PHP_INT_MAX) {
                    $query->where('price', '>=', $minValue);
                }
                if ($maxValue !== PHP_INT_MIN) {
                    $query->where('price', '<=', $maxValue);
                }
            });
        }

        if ($stars && is_array($stars)) {
            $query->whereIn('star', $stars);
        }

        $hotels = $query->paginate(10);
        return view('client.searchresult', compact('hotels'));
    }

    public function booking($hotelId)
    {

    }
}
