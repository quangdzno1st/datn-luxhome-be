<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Voucher\CreateVoucherRequest;
use App\Http\Requests\Api\Voucher\UpdateVoucherRequest;
use App\Services\impl\VoucherServiceImpl;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use League\Flysystem\Exception;

class VoucherController extends Controller
{

    private $voucher;

    public function __construct(VoucherServiceImpl $voucher){
        $this->voucher = $voucher;
    }

    public function index()
    {
        try {
            // Truy xuất tất cả voucher
            $vouchers = $this->voucher->listVoucher();

            // Trả về dữ liệu voucher với thông điệp thành công
            return response()->json([
                'data' => $vouchers,
                'message' => 'Render successfully'
            ], 200);
        } catch (\Exception $e) {
            // Xử lý ngoại lệ và trả về thông điệp lỗi
            return response()->json([
                'error' => 'Failed to retrieve vouchers',
                'message' => $e->getMessage() // Thông điệp chi tiết lỗi (nếu cần)
            ], 500); // 500 Internal Server Error
        }
    }

//    public function create(Request $request)
//    {
//        $data=[
//            'id'=>$request->id,
//            'code'=>$request->code,
//            'description'=>$request->description,
//            'status'=>$request->status,
//            'quantity'=>$request->quantity,
//            'discount_type'=>$request->discount_type,
//            'discount_value'=>$request->discount_value,
//            'start_date'=>$request->start_date,
//            'end_date'=>$request->end_date,
//            'min_price'=>$request->min_price,
//            'max_price'=>$request->max_price,
//            'rank_id'=>$request->rank_id,
//            'conditional_rank'=>$request->conditional_rank,
//            'conditional_total_amount'=>$request->conditional_total_amount,
//
//            ];
//        $voucher= Voucher::query()->create($data);
//        return response()->json([
//            'data'=>$voucher,
//            'message'=>'Thành công'
//        ],201);
//    }

    public function store(CreateVoucherRequest $request)
    {
        try {
            $data = $request->validated();

            $data['id'] = Str::uuid()->toString();

            $voucher=$this->voucher->createVoucher($data);
            return response()->json([
                'data'=>$voucher,
                'message'=>'Thành công'
            ],201);
        }catch (\Exception $e){
            // Xử lý ngoại lệ và trả về thông điệp lỗi
            return response()->json([
                'error' => 'Failed to retrieve vouchers',
                'message' => $e->getMessage() // Thông điệp chi tiết lỗi (nếu cần)
            ], 500); // 500 Internal Server Error
        }

    }

    public function show($id)
    {
        try {
            $voucher=$this->voucher->showVoucher($id);
            return response()->json([
                'data'=>$voucher,
                'message'=>'Thành công voucher'
            ],200);
        }catch (\Exception $e){
            return response()->json([
                'error'=>'Failed to retrieve this voucher',
                'message'=>$e->getMessage()
            ],404);
        }
    }

    public function edit($id)
    {
        //
    }

    public function update(UpdateVoucherRequest $request, $id)
    {
        $data = $request->validated();
        $data['id'] = Str::uuid()->toString();
        $voucher=$this->voucher->updateVoucher($data,$id);
        return response()->json([
            'data'=>$voucher,
            'message'=>'Thành công'
        ],200);
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();

            $voucher = $this->voucher->deleteVoucher($id);

            DB::commit();

            return response()->json([
                'result' => true,
                'message' => 'softdelete successfully',
                'data' => $voucher,
            ],200);

        } catch (Exception $exception)
        {
            return response()->json([
                'result' => false,
                'message' => 'Xóa thất bại',
                'error_message' => $exception->getMessage(),
            ], 404);
        }
    }

    public function restore($id)
    {
        try {
            DB::beginTransaction();

            $voucher = $this->voucher->restoreVoucher($id);

            DB::commit();

            return response()->json([
                'result' => true,
                'message' => 'restored successfully',
                'data' => $voucher,
            ],200);

        } catch (Exception $exception)
        {
            return response()->json([
                'result' => false,
                'message' => 'Khôi phục thất bại',
                'error_message' => $exception->getMessage(),
            ], 404);
        }
    }

    public function destroy($id)
    {

        try {
            DB::beginTransaction();

            $voucher = $this->voucher->forceDeleteVoucher($id);

            DB::commit();

            return response()->json([
                'result' => true,
                'message' => 'forcedelete successfully',
                'data' => $voucher,
            ],200);

        } catch (Exception $exception)
        {
            return response()->json([
                'result' => false,
                'message' => 'forcedelete failed',
                'error_message' => $exception->getMessage(),
            ], 404);
        }
    }

    public function getByCondition($key)
    {
        $vouchers = $this->voucher->getByCondition($key);
        if (isset($vouchers)) {
            return $this->sendSuccess($vouchers);
        }
        return response()->json([
            'result' => false,
            'message' => 'No Found Data',
            'data' => []
        ], Response::HTTP_NOT_FOUND);
    }
}

