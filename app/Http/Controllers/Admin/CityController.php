<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\City\CreateCityRequest;
use App\Http\Requests\Admin\City\UpdateCityRequest;
use App\Http\Resources\CityCollection;
use App\Http\Resources\CityResource;
use App\Models\City;
use App\Repositories\City\CityRepository;
use App\Repositories\Reigion\RegionRepository;
use App\Services\impl\CityServiceImpl;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class CityController extends Controller
{
    protected $cityRepository, $cityService;
    protected $regionRepository;

    const PATH_VIEW = 'admin.cities.';

    public function __construct(CityRepository $cityRepository, CityServiceImpl $cityService, RegionRepository $regionRepository)
    {
        $this->cityRepository = $cityRepository;
        $this->regionRepository = $regionRepository;
        $this->cityService = $cityService;
    }

    public function index()
    {
        $query = City::query()->with('region');

        if (request()->filled('city')) {
            $city = request()->input('city');
            $query->where('name', 'like', '%' . $city . '%');
        }

        if (request()->filled('region')) {
            $region = request()->input('region');
            $query->where('region_id', $region);
        }
        
        $data = $query->orderBy('name')->paginate(10);
        // dd($data->toArray());
        $regions = $this->regionRepository->getAllRegion();

        return view(self::PATH_VIEW . __FUNCTION__, compact('data', 'regions'));
    }


    public function store(CreateCityRequest $request)
    {
        try {
            $data = $request->all();

            $data['thumbnail'] = Storage::put('cities', $data['thumbnail']);

            $city = City::query()->create($data);

            return redirect()->route('admin.cities.index')->with('success', 'Thêm mới thành phố thành công');
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    public function create()
    {
        $regions = $this->regionRepository->getAllRegion();

        return view(self::PATH_VIEW . __FUNCTION__, compact('regions'));
    }

    public function edit($id)
    {
        $regions = $this->regionRepository->getAllRegion();

        $data = $this->cityRepository->detailCity($id);

        return view(self::PATH_VIEW . __FUNCTION__, compact('data', 'regions'));
    }

    public function update(UpdateCityRequest $request, $id)
    {
        $city = City::query()->where('id', $id)->firstOrFail();

        try {
            $data = $request->all();
            
            if ($request->has('thumbnail')) {
                $data['thumbnail'] = Storage::put('cities', $data['thumbnail']);
                Storage::delete($city->thumbnail);
            }
            $city->update($data);

            return redirect()->route('admin.cities.index')->with('success', 'Sửa thành phố thành công');

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $city = $this->cityService->deleteCity($id);

            return redirect()->route('admin.cities.index')->with('success', 'Xóa thành phố thành công');

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    public function trash()
    {
        $data = $this->cityRepository->trash();

        return view(self::PATH_VIEW . __FUNCTION__, compact('data'));

    }

    public function restore($id)
    {
        try {
            $city = $this->cityService->restoreCity($id);

            return redirect()->route('admin.cities.index')->with('success', 'Khôi phục thành phố thành công');

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    public function forceDelete($id)
    {
        $city = City::withTrashed()->where('id', $id)->firstOrFail();

        try {
            $this->cityService->forceDeleteCity($id);
            
            Storage::delete($city->thumbnail);

            return redirect()->route('admin.cities.index')->with('success', 'Xóa vĩnh viễn thành phố thành công');

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }
}
