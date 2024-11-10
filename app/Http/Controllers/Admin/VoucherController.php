<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Api\Voucher\CreateVoucherRequest;
use App\Http\Requests\Api\Voucher\UpdateVoucherRequest;
use App\Models\Voucher;
use App\Services\impl\VoucherServiceImpl;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class VoucherController extends Controller
{

    private $voucher;

    const PATH_DIRECT='admin.voucher.';

    public function __construct(VoucherServiceImpl $voucher){
        $this->voucher = $voucher;
    }

    public function index()
    {
        try {
            // Truy xuất tất cả voucher
            $vouchers = $this->voucher->listVoucher();
//            dd($vouchers);
            // Trả về dữ liệu voucher với thông điệp thành công
            return view(self::PATH_DIRECT.__FUNCTION__, compact('vouchers'));
        } catch (\Exception $e) {
            return Redirect::back()->withErrors('msg', 'Failed to retrieve vouchers');
        }
    }

    public function create()
    {
        return view(self::PATH_DIRECT.__FUNCTION__);
    }

    public function store(CreateVoucherRequest $request)
    {
        try {
            $data = $request->validated();
            $data['code']=Str::upper(Str::random(10));
            $data['id'] = Str::uuid()->toString();

            $voucher=$this->voucher->createVoucher($data);
            return $this->index();
        }catch (\Exception $e){
            return Redirect::back()->withErrors(['msg' => 'Errors: '.$e->getMessage()]);
        }
    }

    public function show($id)
    {
        try {
            $voucher=$this->voucher->showVoucher($id);
            return $voucher;
        }catch (\Exception $e){
            return Redirect::back()->withErrors(['msg' => 'Errors: '.$e->getMessage()]);
        }
    }

    public function edit($id)
    {
        try {
            $voucher=$this->voucher->showVoucher($id);
//            dd($voucher);
            return view(self::PATH_DIRECT.__FUNCTION__, compact('voucher'));
        }catch (\Exception $e){
            return Redirect::back()->withErrors(['msg' => 'Errors: '.$e->getMessage()]);
        }
    }

    public function update(UpdateVoucherRequest $request, $id)
    {
        try {

            if ($request->validated()){
                $data = $request->validated();
                $data['code']=Str::upper(Str::random(10));
                $data['id'] = Str::uuid()->toString();
                $voucher=$this->voucher->updateVoucher($data,$id);
                return $this->index();
            }else{
                return \redirect()->back()->withErrors(['msg' => 'Errors']);
            }
        }catch (\Exception $e){
            return Redirect::back()->withErrors(['msg' => 'Errors: '.$e->getMessage()]);
        }

    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();

            $voucher = $this->voucher->deleteVoucher($id);

            DB::commit();

            return $this->index();

        } catch (Exception $exception)
        {
            return Redirect::back()->withErrors(['msg' => 'Errors: '.$exception->getMessage()]);
        }
    }

    public function list_trash(){
        $trashedVouchers = Voucher::onlyTrashed()->get();
//        dd($trashedVouchers);
        return view(self::PATH_DIRECT.__FUNCTION__, compact('trashedVouchers'));
    }

    public function restore($id)
    {
        try {
            DB::beginTransaction();

            $voucher = $this->voucher->restoreVoucher($id);

            DB::commit();

            return $this->index();

        } catch (Exception $exception)
        {
            return Redirect::back()->withErrors(['msg' => 'Errors: '.$exception->getMessage()]);

        }
    }

    public function destroy($id)
    {

        try {
            DB::beginTransaction();

            $voucher = $this->voucher->forceDeleteVoucher($id);

            DB::commit();

            return $this->index();

        } catch (Exception $exception)
        {
            return Redirect::back()->withErrors(['msg' => 'Errors: '.$exception->getMessage()]);
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

