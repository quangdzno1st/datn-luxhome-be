<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Service\CreateRequest;
use App\Http\Requests\Api\Service\UpdateRequest;
use App\Models\Service;
use App\Services\impl\ServiceServiceImpl;
use Illuminate\Http\Request;
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

//        dd($services->toArray());

        return view(self::PATH_VIEW . __FUNCTION__, compact('services'));
    }

    public function store(CreateRequest $request)
    {
        $data = $request->validated();

        try {
            DB::beginTransaction();

            $service = $this->service->createNew($data);

            DB::commit();

            return back();

        } catch (\Exception $exception) {
            return back()->withErrors(['msg' => $exception->getMessage()]);
        }
    }

    public function update(UpdateRequest $request,  string $id)
    {
        $data = $request->validated();
        try {
            DB::beginTransaction();

            $service = $this->service->update($data, $id);

            DB::commit();

            return back();

        } catch (\Exception $exception) {
            return back()->withErrors(['msg' => $exception->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        $service = $this->service->forceDelete($id);

        return back();
    }
}