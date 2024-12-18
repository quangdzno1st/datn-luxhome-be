<?php

namespace App\Http\Controllers\Admin;

use App\Constant\Enum\UserRankEnum;
use App\Exceptions\RespException;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Api\Voucher\CreateVoucherRequest;
use App\Http\Requests\Api\Voucher\UpdateVoucherRequest;
use App\Http\Requests\VoucherRequest;
use App\Models\Hotel;
use App\Models\Order;
use App\Models\User;
use App\Models\Voucher;
use App\Models\Wallet;
use App\Repositories\User\UserRepository;
use App\Services\FileUploadService;
use App\Services\impl\VoucherServiceImpl;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class VoucherController extends Controller
{

    private FileUploadService $fileUploadService;
    private UserRepository $userRepos;

    private $voucher;

    const PATH_DIRECT = 'admin.voucher.';

    public function __construct(
        VoucherServiceImpl $voucher,
        FileUploadService  $fileUploadService,
        UserRepository     $userRepos
    )
    {
        $this->voucher = $voucher;
        $this->fileUploadService = $fileUploadService;
        $this->userRepos = $userRepos;
    }

    public function index(Request $request)
    {
//        dd(UserRankEnum::NGUOI_DUNG_HANG_NHAT->value);
        $vouchers = $this->voucher->listVoucher();
        $hotels = Hotel::query()->select('id', 'name')->get();
        if ($_GET) $vouchers = $this->searchVoucher($request->all());

//        dd($vouchers);
        return view(self::PATH_DIRECT . __FUNCTION__, compact('vouchers', 'hotels'));
    }

    public function create()
    {
        if(Auth::user()->type == User::STAFF ){
            return redirect()->back()->with('error', 'Không được thêm voucher!');
        }else{
            return view(self::PATH_DIRECT . __FUNCTION__);
        }
//        return view(self::PATH_DIRECT . __FUNCTION__);
    }

    public function store(CreateVoucherRequest $request)
    {
        $data = $request->all();
        $data['code'] = Str::upper(Str::random(10));
        $data['thumbnail'] = $this->fileUploadService->storeLocal($request->file('thumbnail'));
        $data['id'] = Str::uuid()->toString();

        if (Auth::user()->type == 3) $data['hotel_id'] = Auth::user()->org_id;

        $voucher = $this->voucher->createVoucher($data);
        return redirect()->route('admin.vouchers.index')->with('success', 'Thêm mã giảm giá thành công!');
    }

    public function edit($id)
    {
        $voucher = $this->getNonNullById($id);
        if(Auth::user()->type == User::HOTELIER && $voucher->hotel_id != Auth::user()->org_id && $voucher->hotel_id!=null|| Auth::user()->type == User::STAFF ){
            return redirect()->back()->with('error', 'Không được vào voucher này!');
        }else{
            return view(self::PATH_DIRECT . __FUNCTION__, compact('voucher'));
        }
//        if (Auth::user()->type == User::HOTELIER && $voucher->hotel_id == Auth::user()->org_id || Auth::user()->type == User::ADMIN || $voucher->hotel_id == null) {
//            return view(self::PATH_DIRECT . __FUNCTION__, compact('voucher'));
//        } else {
//            return redirect()->back()->with('error', 'Không được vào voucher này!');
//        }
    }

    public function update(UpdateVoucherRequest $request, $id)
    {
        try {

            // if ($request->validated()) {
            $data = $request->all();
            // dd($data);
            DB::beginTransaction();

            $voucher = $this->getNonNullById($id);
            // $data['code'] = Str::upper(Str::random(10));
            // $data['id'] = Str::uuid()->toString();

            if ($request->hasFile('thumbnail')) {
                $data['thumbnail'] = $this->fileUploadService->storeLocal($request->file('thumbnail'));
            }

            $voucher = $this->voucher->updateVoucher($data, $id);

            DB::commit();

            return back()->with('success', 'Cập nhật mã giảm giá thành công!');
            // } else {
            //     return \redirect()->back()->with('error' , 'Errors');
            // }
        } catch (\Exception $e) {
            return Redirect::back()->with('error', 'Errors: ' . $e->getMessage());
        }
    }

    /**
     * @throws RespException
     */
    private function getNonNullById($id)
    {
        $voucher = $this->voucher->showVoucher($id);

        if (empty($voucher)) {
            throw new RespException('Không tìm thấy phiếu giảm giá');
        }

        return $voucher;
    }

    /**
     * @throws RespException
     */
    public function delete($id)
    {
        try {
            DB::beginTransaction();

            $voucher = $this->getNonNullById($id);
            $this->voucher->deleteVoucher($voucher['id']);

            DB::commit();

            return \redirect()->back()->with('success','Xóa thành công');
        } catch (\Exception $exception) {
            return Redirect::back()->with('error', 'Errors: ' . $exception->getMessage());
        }
    }

    // public function list_trash()
    // {
    //     $trashedVouchers = Voucher::onlyTrashed()->get();
    //     return view(self::PATH_DIRECT . __FUNCTION__, compact('trashedVouchers'));
    // }

    /**
     * @throws RespException
     */
    // public function destroy($id)
    // {

    //     try {
    //         DB::beginTransaction();

    //         $voucher = $this->getNonNullById($id);
    //         $this->voucher->forceDeleteVoucher($voucher['id']);

    //         DB::commit();

    //         return $this->index();
    //     } catch (\Exception $exception) {
    //         return Redirect::back()->with('error', 'Errors: ' . $exception->getMessage());
    //     }
    // }

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

    public function issueVoucher(VoucherRequest $request)
    {
        if (!is_null($request->input("total_amount_ordered_from")) && !is_null($request->input("total_amount_ordered_to"))) {
            if ($request->input("total_amount_ordered_from") > $request->input("total_amount_ordered_to")) {
                return response()->json([
                    'message' => 'Trường "Tổng chi tiêu đến" phải lớn hơn hoặc bằng "Tổng chi tiêu từ".',
                ], 404);
            }
        }

        $users = $this->userRepos->getByRankAndTotalAmountOrdered($request);

        $userIds = $users->pluck('id')->toArray(); // Lấy danh sách ID

        $voucherMapByCode = $this->voucher->getMapByCode($request->vouchers);
        if (empty($voucherMapByCode->toArray())) {
            return response()->json([
                'message' => 'Không tìm thấy phiếu giảm giá',
            ], 404);
        }

        $voucherIds = $voucherMapByCode->pluck('id')->toArray();

        $existingRecords = Wallet::query()->select('wallets.voucher_id', 'wallets.user_id', 'vouchers.code')
            ->join('vouchers', 'vouchers.id', '=', 'wallets.voucher_id')
            ->whereIn('user_id', $userIds)
            ->whereIn('vouchers.id', $voucherIds)
            ->get()
            ->toArray();

        $existingMap = [];
        $userVoucherSendMailMap = [];

        foreach ($existingRecords as $record) {
            $existingMap[$record['user_id']][$record['code']] = true;
        }

        $vouchers = [];
        foreach ($users as $user) {
            foreach ($voucherMapByCode as $voucher) {

                if (!isset($voucher)) {
                    return response()->json([
                        'message' => 'Không tìm thấy phiếu giảm giá' . $voucher['code'],
                    ], 404);
                }

                if (!isset($existingMap[$user['id']][$voucher['code']])) {
                    $vouchers[] = [
                        'id' => Str::uuid()->toString(),
                        'user_id' => $user["id"],
                        'voucher_id' => $voucher["id"],
                    ];

                    $userVoucherSendMailMap[$user['email']][] = $voucher;
                }
            }
        }
        if (!empty($vouchers)) {
            Wallet::query()->insert($vouchers);
            $this->sendMailToUser($userVoucherSendMailMap);
        }

        return response()->json([
            'message' => 'Phát phiếu giảm giá thành công.',
        ], 200);
    }

    public function searchVoucher($data)
    {
        if (Auth::user()->type == User::ADMIN) {
            $vouchers = Voucher::query()
                ->orderByDesc('created_at');
        } else {
            $vouchers = Voucher::query()
                ->orderByDesc('created_at')
                ->where('hotel_id', Auth::user()->org_id)
                ->orWhereNull('hotel_id');
        }

        // Điều kiện lọc theo hotel
        if (isset($data['hotel']) && $data['hotel'] != '') {
            $vouchers = $vouchers->where(function ($query) use ($data) {
                $query->where('hotel_id', $data['hotel']);
            });
        }

        // Điều kiện lọc theo code
        if (isset($data['code']) && $data['code'] != '') {
            $vouchers = $vouchers->where('code', 'LIKE', "%{$data['code']}%");
        }

        // Paginate sau khi truy vấn được xây dựng đầy đủ
        return $vouchers->paginate(10);
    }


    private function sendMailToUser($userVoucherSendMailMap)
    {
    }
}
