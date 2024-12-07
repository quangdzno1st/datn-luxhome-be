<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AmenitiesRequest;
use App\Http\Requests\Admin\Service\CreateRequest;
use App\Http\Requests\Admin\Service\UpdateRequest;
use App\Models\Service;
use App\Services\impl\AmenitiesServiceImpl;
use App\Services\impl\ServiceServiceImpl;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;

class AmenitiesController extends Controller
{
    const PATH_VIEW = 'admin.amenities.';

    protected $amenitie;

    public function __construct(AmenitiesServiceImpl $amenitie)
    {
        $this->amenitie = $amenitie;
    }
    public function index()
    {
        $amenities = $this->amenitie->getAll(request());

        return view(self::PATH_VIEW . __FUNCTION__, compact('amenities'));
    }

    public function store(AmenitiesRequest $request)
    {
        $data = $request->validated();

        try {
            DB::beginTransaction();

            $amenitie = $this->amenitie->createNew($data);

            DB::commit();

            return back()->with('success', 'Thao tác  thành công!');

        } catch (\Exception $exception) {
            return back()->withErrors(['error' => $exception->getMessage()]);
        }
    }

    public function update(AmenitiesRequest $request,  string $id)
    {
        $data = $request->validated();
        try {
            DB::beginTransaction();

            $amenitie = $this->amenitie->update($data, $id);

            DB::commit();

            return back()->with('success', 'Thao tác  thành công!');

        } catch (\Exception $exception) {
            return back()->withErrors(['error' => $exception->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $amenitie = $this->amenitie->forceDelete($id);
            
            DB::commit();

            return back()->with('success', 'Thao tác  thành công!');

        } catch (\Exception $exception) {
            return back()->withErrors(['error' => $exception->getMessage()]);
        }

    }
}