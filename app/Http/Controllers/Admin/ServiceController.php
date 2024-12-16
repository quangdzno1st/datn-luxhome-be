<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Service\CreateRequest;
use App\Http\Requests\Admin\Service\UpdateRequest;
use App\Models\Hotel;
use App\Models\Service;
use App\Models\User;
use App\Services\impl\ServiceServiceImpl;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;

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
        if (Auth::user()->org_id != null && Auth::user()->type != User::ADMIN) {
            $org_id = Auth::user()->org_id;
        } else {
            $org_id = '';
        }
        $services = $this->service->getAll(request(), $org_id);

        $typesService = Service::TYPE_SERVICE;

        $hotels = Hotel::all();

        return view(self::PATH_VIEW . __FUNCTION__, compact('services', 'typesService', 'hotels'));
    }

    public function store(CreateRequest $request)
    {
        $data = $request->all();

        $data['status'] = $data['status'] ?? 2;

        try {
            DB::beginTransaction();

            $service = $this->service->createNew($data);

            DB::commit();

            return back()->with('success', 'Thêm mới thành công');

        } catch (\Exception $exception) {
            return back()->with('error' , $exception->getMessage());
        }
    }

    public function update(UpdateRequest $request,  string $id)
    {
        $data = $request->all();

        $data['status'] = $data['status'] ?? 2;

        try {
            DB::beginTransaction();

            $service = $this->service->update($data, $id);

            DB::commit();

            return back()->with('success', 'Cập nhật thành công');

        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $service = $this->service->forceDelete($id);
            
            DB::commit();

            return back()->with('success', 'Xóa thành công');

        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }

    }
}