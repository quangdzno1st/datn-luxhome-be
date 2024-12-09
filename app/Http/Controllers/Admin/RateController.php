<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RateController extends Controller
{
    const PATH_VIEW = 'admin.rates.';

    public function listRatesAllHotels()
    {
        $query = Hotel::with('rates');

        if (request()->has('keyword')) {
            $keyword = request()->input('keyword');
            $query->where('name', 'like', "%$keyword%");
        }

        $ratesOfHotels = $query->latest('created_at')->paginate(10);
        
        return view(self::PATH_VIEW . 'index', compact('ratesOfHotels'));
    }

    public function listRatesOneHotel($hotelId)
    {
        $hotel = Hotel::query()->where('id', $hotelId)->firstOrFail();

        $query = Rate::withoutTrashed()->with('user', 'comment')->where('hotel_id', $hotelId);

        if (request()->filled('content')) {
            $content = request()->input('content');
            $query->where('content', 'like', "%$content%");
        }

        if (request()->filled('name')) {
            $name = request()->input('name');
            $query->whereHas('user', function($query) use ($name) {
                $query->where('name', 'like', "%$name%");
            });
        }

        if (request()->filled('rate')) {
            $rate = request()->input('rate');
            $query->where('rate', $rate);
        }

        if (request()->filled('status')) {
            $status = request()->input('status');
            if ($status == 1) {
                $query->has('comment');
            } else {
                $query->doesntHave('comment');
            }
        }

        $rates = $query->latest('created_at')->paginate(10);
        // dd($rates->toArray());

        return view(self::PATH_VIEW . 'list-rates-one-hotel', compact('hotel', 'rates'));
    }

    public function listRatesOneHotelTrash($hotelId)
    {
        $hotel = Hotel::query()->where('id', $hotelId)->firstOrFail();

        $query = Rate::onlyTrashed()->with('user', 'comment')->where('hotel_id', $hotelId);

        if (request()->filled('content')) {
            $content = request()->input('content');
            $query->where('content', 'like', "%$content%");
        }

        if (request()->filled('name')) {
            $name = request()->input('name');
            $query->whereHas('user', function($query) use ($name) {
                $query->where('name', 'like', "%$name%");
            });
        }

        if (request()->filled('rate')) {
            $rate = request()->input('rate');
            $query->where('rate', $rate);
        }

        // if (request()->filled('status')) {
        //     $status = request()->input('status');
        //     if ($status == 1) {
        //         $query->has('comment');
        //     } else {
        //         $query->doesntHave('comment');
        //     }
        // }

        $rates = $query->latest('created_at')->paginate(10);

        return view(self::PATH_VIEW . 'list-rates-one-hotel-trash', compact('hotel', 'rates'));
    }

    public function getRatesByHotelIdOfHotelier()
    {
        $hotelId = Auth::user()->org_id;

        $hotel = Hotel::query()->where('id', $hotelId)->firstOrFail();

        $query = Rate::withoutTrashed()->with('user', 'comment')->where('hotel_id', $hotelId);

        if (request()->filled('content')) {
            $content = request()->input('content');
            $query->where('content', 'like', "%$content%");
        }

        if (request()->filled('name')) {
            $name = request()->input('name');
            $query->whereHas('user', function($query) use ($name) {
                $query->where('name', 'like', "%$name%");
            });
        }

        if (request()->filled('rate')) {
            $rate = request()->input('rate');
            $query->where('rate', $rate);
        }

        if (request()->filled('status')) {
            $status = request()->input('status');
            if ($status == 1) {
                $query->has('comment');
            } else {
                $query->doesntHave('comment');
            }
        }

        $rates = $query->latest('created_at')->paginate(10);

        return view(self::PATH_VIEW . 'list-rates-one-hotel', compact('hotel', 'rates'));
    }

    public function getRatesByHotelIdOfHotelierTrash()
    {
        $hotelId = Auth::user()->org_id;

        $hotel = Hotel::query()->where('id', $hotelId)->firstOrFail();

        $query = Rate::onlyTrashed()->with('user', 'comment')->where('hotel_id', $hotelId);

        if (request()->filled('content')) {
            $content = request()->input('content');
            $query->where('content', 'like', "%$content%");
        }

        if (request()->filled('name')) {
            $name = request()->input('name');
            $query->whereHas('user', function($query) use ($name) {
                $query->where('name', 'like', "%$name%");
            });
        }

        if (request()->filled('rate')) {
            $rate = request()->input('rate');
            $query->where('rate', $rate);
        }

        // if (request()->filled('status')) {
        //     $status = request()->input('status');
        //     if ($status == 1) {
        //         $query->has('comment');
        //     } else {
        //         $query->doesntHave('comment');
        //     }
        // }

        $rates = $query->latest('created_at')->paginate(10);

        return view(self::PATH_VIEW . 'list-rates-one-hotel-trash', compact('hotel', 'rates'));
    }

    public function rateHidden($rateId)
    {
        $rate = Rate::withoutTrashed()->where('id', $rateId)->firstOrFail();
        $rate->delete();
        return back()->with('success', 'Bạn đã ẩn 1 đánh giá');
    }

    public function rateRestore($rateId)
    {
        $rate = Rate::onlyTrashed()->where('id', $rateId)->firstOrFail();
        $rate->restore();
        return back()->with('success', 'Bạn đã khôi phục 1 đánh giá');
    }

    public function rateDestroy($rateId)
    {
        $rate = Rate::withTrashed()->where('id', $rateId)->firstOrFail();
        $rate->forceDelete();
        return back()->with('success', 'Bạn đã xóa vĩnh viễn 1 đánh giá');
    }
}
