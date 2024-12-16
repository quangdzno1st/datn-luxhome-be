<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Hotel\CreateHotelRequest;
use App\Http\Requests\Api\Hotel\UpdateHotelRequest;
use App\Models\Hotel;
use App\Models\User;
use App\Repositories\Hotel\HotelRepository;
use App\Repositories\Reigion\RegionRepository;
use App\Services\impl\HotelServiceImpl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HotelController extends Controller
{
    protected $hotelRepository;
    protected $regionRepository;
    protected $hotelService;

    const PATH_VIEW = 'admin.hotels.';

    public function __construct(HotelRepository $hotelRepository, HotelServiceImpl $hotelService, RegionRepository $regionRepository)
    {
        $this->hotelRepository = $hotelRepository;
        $this->regionRepository = $regionRepository;
        $this->hotelService = $hotelService;
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->type != User::ADMIN) {
            $data = $this->hotelRepository->getAllForHotelier();
        } else {
            $query = Hotel::query();
            if (request()->filled('keyword')) {
                $keyword = request()->input('keyword');
                $query->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('location', 'like', '%' . $keyword . '%');
            }
            $data = $query->latest('created_at')->paginate(10);
        }

        return view(self::PATH_VIEW . __FUNCTION__, compact('data'));
    }

    public function create()
    {
        $regions = $this->regionRepository->getAllRegion();
        return view(self::PATH_VIEW . __FUNCTION__, compact('regions'));
    }

    public function store(CreateHotelRequest $request)
    {
        try {
            $data = $request->validated();
            //            dd($data);
            $data['status'] = $request->status ? 1 : 0;
            $this->hotelService->createNewHotel($data);

            return redirect()->route('admin.hotels.index')->with('success', 'Thêm mới khách sạn thành công');
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $data = $this->hotelRepository->detailHotel($id);

        return view(self::PATH_VIEW . __FUNCTION__, compact('data'));
    }

    public function edit($id)
    {
        $data = $this->hotelRepository->detailHotel($id);
        $regions = $this->regionRepository->getAllRegion();
        return view(self::PATH_VIEW . __FUNCTION__, compact('data', 'regions'));
    }

    public function update(UpdateHotelRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $data['status'] = $request->status ? 1 : 0;
            $this->hotelService->updateHotel($data, $id);

            return redirect()->back()->with('success', 'Sửa khách sạn thành công');
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $hotel = $this->hotelService->deleteHotel($id);

            return redirect()->route('admin.hotels.index')->with('success', 'Xóa khách sạn thành công');
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    // thung rac'
    public function trash()
    {
        $data = $this->hotelRepository->trash();

        return view(self::PATH_VIEW . __FUNCTION__, compact('data'));
    }

    public function restore($id)
    {

        try {
            $hotel = $this->hotelService->restoreHotel($id);
            return redirect()->route('admin.hotels.index')->with('success', 'Khôi phục khách sạn thành công');
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    public function forceDelete($id)
    {
        try {
            $hotel = $this->hotelService->forceDeleteHotel($id);
            return redirect()->route('admin.hotels.index')->with('success', 'Xóa vĩnh viễn khách sạn thành công');
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }
}
