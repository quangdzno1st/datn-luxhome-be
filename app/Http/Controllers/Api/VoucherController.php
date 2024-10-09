<?php

namespace App\Http\Controllers\Api;

//use App\Http\Controllers\CommonKeyCodeController;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
//    protected $commonKeyCodeService;
//    public function __construct(CommonKeyCodeService $commsonKeyCodeService)
//    {
//        $this->commonKeyCodeService = $commonKeyCodeService;
//    }

    public function index()
    {
        try {
            // Truy xuất tất cả voucher
            $vouchers = Voucher::all();

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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
//            $randomCode=rand();
//            return $randomCode;
            $data=[
                'id'=>Str::uuid()->toString(),
//                'code'=>$this->commonKeyCodeService->genNewKeyCode('VC','1001','26'),
                'code'=>$request->code,
                'description'=>$request->description,
                'status'=>$request->status,
                'quantity'=>$request->quantity,
                'discount_type'=>$request->discount_type,
                'discount_value'=>$request->discount_value,
                'start_date'=>$request->start_date,
                'end_date'=>$request->end_date,
                'min_price'=>$request->min_price,
                'max_price'=>$request->max_price,
                'rank_id'=>$request->rank_id,
                'conditional_rank'=>$request->conditional_rank,
                'conditional_total_amount'=>$request->conditional_total_amount,
            ];
            $voucher= Voucher::query()->create($data);
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $voucher=Voucher::query()->find($id);
            return response()->json([
                'data'=>$voucher,
                'message'=>'Thành công voucher'
            ],201);
        }catch (\Exception $e){
            return response()->json([
                'error'=>'Failed to retrieve this voucher',
                'message'=>$e->getMessage()
            ],404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $voucher=Voucher::query()->find($id);
        $data=[
            'id'=>Str::uuid()->toString(),
            'code'=>$request->code,
            'description'=>$request->description,
            'status'=>$request->status,
            'quantity'=>$request->quantity,
            'discount_type'=>$request->discount_type,
            'discount_value'=>$request->discount_value,
            'start_date'=>$request->start_date,
            'end_date'=>$request->end_date,
            'min_price'=>$request->min_price,
            'max_price'=>$request->max_price,
            'rank_id'=>$request->rank_id,
            'conditional_rank'=>$request->conditional_rank,
            'conditional_total_amount'=>$request->conditional_total_amount,
        ];
        $newData=$voucher->update($data);
        return response()->json([
            'data'=>$newData,
            'message'=>'Thành công'
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Voucher::query()->delete($id);
        return response()->json([
            'message'=>'Xóa thành công'
        ],200);
    }
}
