<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\City\CreateCityRequest;
use App\Http\Requests\Admin\City\UpdateCityRequest;
use App\Http\Resources\CityCollection;
use App\Http\Resources\CityResource;
use App\Repositories\City\CityRepository;
use App\Repositories\Reigion\RegionRepository;
use App\Services\impl\CityServiceImpl;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
        $data = $this->cityRepository->getAllCity();
        // dd($data->toArray());
        $regions = $this->regionRepository->getAllRegion();

        return view(self::PATH_VIEW . __FUNCTION__, compact('data', 'regions'));
    }


    public function store(CreateCityRequest $request)
    {
        try {
            $data = $request->all();

            $city = $this->cityService->createNewCity($data);

            return redirect()->route('admin.cities.index')->with('success', 'Thêm mới thành phố thành công');
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return back()->with('errors', $e->getMessage());
        }
    }

    // public function show($id)
    // {
    //     $data = $this->cityRepository->detailCity($id);

    //     return view(self::PATH_VIEW . __FUNCTION__, compact('data'));
    // }

    // public function edit($id)
    // {
    //     $regions = $this->regionRepository->getAllRegion();

    //     $data = $this->cityRepository->detailCity($id);

    //     return view(self::PATH_VIEW . __FUNCTION__, compact('data', 'regions'));
    // }

    public function update(UpdateCityRequest $request, $id)
    {
        try {
            $data = $request->all();
            $city = $this->cityService->updateCity($data, $id);

            return redirect()->route('admin.cities.index')->with('success', 'Sửa thành phố thành công');

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return back()->with('errors', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $city = $this->cityService->deleteCity($id);

            return redirect()->route('admin.cities.index')->with('success', 'Xóa thành phố thành công');

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return back()->with('errors', $e->getMessage());
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
            return back()->with('errors', $e->getMessage());
        }
    }

    public function forceDelete($id)
    {
        try {
            $this->cityService->forceDeleteCity($id);

            return redirect()->route('admin.cities.index')->with('success', 'Xóa vĩnh viễn thành phố thành công');

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return back()->with('errors', $e->getMessage());
        }
    }
}
