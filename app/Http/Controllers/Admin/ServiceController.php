<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Service\ServiceRequest;
use App\Models\Service;
use App\Services\impl\ServiceServiceImpl;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    const PATH_VIEW = 'admin.services.';

    protected $service;

    public function __construct(ServiceServiceImpl $service)
    {
        $this->service = $service;
    }
    public function index()
    {
        $services = $this->service->getAll(request());

        $typesService = Service::TYPE_SERVICE;

        return view(self::PATH_VIEW . __FUNCTION__, compact('services', 'typesService'));
    }

    public function store(ServiceRequest $request)
    {
        $data = $request->validated();

        try {
            DB::beginTransaction();

            $service = $this->service->createNew($data);

            DB::commit();

            return back()->with('success', 'Thêm mới thành công');

        } catch (\Exception $exception) {
            return back()->withErrors(['error' => $exception->getMessage()]);
        }
    }

    public function update(ServiceRequest $request,  string $id)
    {
        $data = $request->validated();
        try {
            DB::beginTransaction();

            $service = $this->service->update($data, $id);

            DB::commit();

            return back()->with('success', 'Cập nhật thành công');

        } catch (\Exception $exception) {
            return back()->withErrors(['error' => $exception->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        $service = $this->service->forceDelete($id);

        return back()->with('success', 'Xóa thành công');
    }
}