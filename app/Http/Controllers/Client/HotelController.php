<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Models\Hotel;
use App\Repositories\CatalogueRoom\CatalogueRoomRepository;
use App\Repositories\Hotel\HotelRepository;
use Carbon\Carbon;

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

    public function show($id)
    {
        $searchData = session('search_data');
        $hotel = Hotel::query()->findOrFail($id);
        $filteredData = collect($searchData)->filter(function ($item) use ($hotel) {
            return $item['hotel_id'] === $hotel->id;
        });
//        dd($filteredData);
        return view('client.hotel', compact('filteredData', 'hotel'));
    }

    public function search(SearchRequest $request)
    {
        $data = $this->catalogueRoomRepository->searchByPage($request);
        session(['search_data' => $data]);
        if (isset($request->check) && $request->check) {
          return redirect()->route('hotel.show', ['hotel_id' => $request->hotel_id]);
        }
        $hotelIds = $data->pluck('hotel_id')->unique();
        $hotels = Hotel::query()->whereIn('id', $hotelIds)->where('city_id', $request->city_id)->paginate(10);
        return view('client.searchresult', compact('hotels'));
    }

    public function booking($hotelId)
    {

    }
}
