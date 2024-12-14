<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Region\CreateReigonRequest;
use App\Http\Requests\Admin\Region\UpdateRegionRequest;
use App\Http\Resources\RegionCollection;
use App\Http\Resources\RegionResource;
use App\Models\Region;
use App\Repositories\Reigion\RegionRepository;
use App\Services\impl\RegionServiceImpl;
use Illuminate\Http\Response;

class RegionController extends Controller
{
    protected $regionRepository, $regionService;

    const PATH_VIEW = 'admin.regions.';
    public function __construct(RegionRepository $regionRepository, RegionServiceImpl $regionService)
    {
        $this->regionRepository = $regionRepository;
        $this->regionService = $regionService;
    }

    public function index()
    {
        $query = Region::query();

        if (request()->filled('keyword')) {
            $keyword = request()->input('keyword');
            $query->where('name', 'like', '%' . $keyword . '%');
        }

        $data = $query->paginate(10);

        return view(self::PATH_VIEW . __FUNCTION__, compact('data'));
    }

    public function show($id)
    {
        $data = $this->regionRepository->regionDetail($id);

        return view(self::PATH_VIEW . __FUNCTION__, compact('data'));
    }

    public function store(CreateReigonRequest $request)
    {
        try {
            $data = $request->all();

            $region = $this->regionService->createNewReigon($data);

            return redirect()->route('admin.regions.index')->with('success', 'Thêm mới miền thành công');
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }


    public function update(UpdateRegionRequest $request, $id)
    {
        try {
            $data = $request->all();

            $region = $this->regionService->updateReigon($data, $id);

            return redirect()->route('admin.regions.index')->with('success', 'Sửa miền thành công');

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $region = $this->regionService->deleteReigon($id);

            return redirect()->route('admin.regions.index')->with('success', 'Xóa miền thành công');

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    public function trash()
    {
        $data = $this->regionRepository->trash();

        return view(self::PATH_VIEW . __FUNCTION__, compact('data'));
    }

    public function restore($id)
    {
        try {
            $region = $this->regionService->restoreReigon($id);

            return redirect()->route('admin.regions.index')->with('success', 'Khôi phục miền thành công');

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    public function forceDelete($id)
    {
        try {
            $region = $this->regionService->forceDeleteReigon($id);

            return redirect()->route('admin.regions.index')->with('success', 'Xóa vĩnh viễn miền thành công');

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }
}
