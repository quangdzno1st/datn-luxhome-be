<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Rate\CreateRateRequest;
use App\Http\Requests\Api\Rate\UpdateRateRequest;
use App\Models\Rate;
use App\Services\impl\RateServiceImpl;
use Illuminate\Support\Facades\Auth;

class RateController extends Controller
{
    private $rate;

    public function __construct(RateServiceImpl $rate){
        $this->rate = $rate;
    }
    // Lấy tất cả rates kèm thông tin user và hotel
    public function index($numRecord)
    {
        try {
            $rates = $this->rate->listRate($numRecord);
            return response()->json($rates, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve vouchers',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $rate = Rate::with(['user', 'hotel'])->findOrFail($id);

            return response()->json([
                'id' => $rate->id,
                'user_name' => $rate->user->name?? 'Unknown User',
                'hotel_name' => $rate->hotel->name?? 'Unknown User',
                'rate' => $rate->rate,
                'content' => $rate->content,
                'created_at' => $rate->created_at,
                'updated_at' => $rate->updated_at,
                'deleted_at' => $rate->deleted_at
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve rate',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(CreateRateRequest $request)
    {
        try {
            $user_id = auth()->id(); // auth()->id() đã đủ để xác nhận người dùng đăng nhập
            $hotel_id = $request->route('hotel_id');
            $validated=$request;
            $rate = Rate::create([
                'user_id' => $user_id,
                'hotel_id' => $hotel_id,
                'rate' => $validated['rate'],
                'content' => $validated['content'],
            ]);
            return response()->json($rate, 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to save the rate',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    

    public function update(UpdateRateRequest $request, $id)
    {
        try{
            $validated = $request;
            $rate = Rate::findOrFail($id);
            $rate->update($validated);
            return response()->json($rate, 200);
        }catch(\Exception $e){
            return response()->json([
                'error' => 'Failed to save the rate',
                'message' => $e->getMessage()
            ], 500);
        }

    }

    public function destroy($id)
    {
        $rate = $this->rate->deleteRate($id);
        $rate->delete();
        return response()->json(null, 204);
    }

    public function forceDelete($id)
    {
        $rate = Rate::withTrashed()->findOrFail($id);
        $rate->forceDelete(); // Xóa cứng
        return response()->json(null, 204);
    }

    public function restore($id)
    {
        $rate = Rate::withTrashed()->findOrFail($id);
        $rate->restore(); // Khôi phục bản ghi
        return response()->json($rate, 200);
    }
    public function getAverageRate($hotel_id)
    {
        try {
            $averageRate = $this->rate->averageRate($hotel_id);
            if ($averageRate === null) {
                return response()->json([
                    'hotel_id' => $hotel_id,
                    'average_rate' => 0,
                    'message' => 'No ratings found for this hotel.'
                ], 200);
            }

            return response()->json([
                'hotel_id' => $hotel_id,
                'average_rate' => round($averageRate, 2)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve vouchers',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
