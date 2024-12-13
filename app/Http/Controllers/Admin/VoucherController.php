<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\RespException;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Api\Voucher\CreateVoucherRequest;
use App\Http\Requests\Api\Voucher\UpdateVoucherRequest;
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
    ) {
        $this->voucher = $voucher;
        $this->fileUploadService = $fileUploadService;
        $this->userRepos = $userRepos;
    }

    public function index(Request $request)
    {
        $vouchers = $this->voucher->listVoucher();
        if ($_GET) return $this->searchVoucher($request->all());

        return view(self::PATH_DIRECT . __FUNCTION__, compact('vouchers'));
    }

    public function create()
    {
        return view(self::PATH_DIRECT . __FUNCTION__);
    }

    public function store(CreateVoucherRequest $request)
    {
        $data = $request->all();
        $data['code'] = Str::upper(Str::random(10));
        $data['thumbnail'] = $this->fileUploadService->storeLocal($request->file('thumbnail'));
        $data['id'] = Str::uuid()->toString();

        if(Auth::user()->type==3) $data['hotel_id'] = Auth::user()->org_id;

        $voucher = $this->voucher->createVoucher($data);
        return redirect()->route('admin.vouchers.index')->with('success', 'Thêm mã giảm giá thành công!');
    }

    public function edit($id)
    {
        try {
            $voucher = $this->getNonNullById($id);

            // dd($voucher->toArray());
            return view(self::PATH_DIRECT . __FUNCTION__, compact('voucher'));
        } catch (\Exception $e) {
            return Redirect::back()->with('error', 'Errors: ' . $e->getMessage());
        }
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

            return \redirect()->back();
        } catch (\Exception $exception) {
            return Redirect::back()->with('error', 'Errors: ' . $exception->getMessage());
        }
    }

    public function list_trash()
    {
        $trashedVouchers = Voucher::onlyTrashed()->get();
        return view(self::PATH_DIRECT . __FUNCTION__, compact('trashedVouchers'));
    }

    public function restore($id)
    {
        try {
            DB::beginTransaction();

            $voucher = $this->voucher->restoreVoucher($id);

            DB::commit();

            return $this->index();
        } catch (\Exception $exception) {
            return Redirect::back()->with('error', 'Errors: ' . $exception->getMessage());
        }
    }

    /**
     * @throws RespException
     */
    public function destroy($id)
    {

        try {
            DB::beginTransaction();

            $voucher = $this->getNonNullById($id);
            $this->voucher->forceDeleteVoucher($voucher['id']);

            DB::commit();

            return $this->index();
        } catch (\Exception $exception) {
            return Redirect::back()->with('error', 'Errors: ' . $exception->getMessage());
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

    /**
     * @throws RespException
     */
    public function issueVoucher(Request $request)
    {

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
        $vouchers = Voucher::query();

        if ($data) {
            $vouchers = $vouchers->where('code', 'LIKE', "%{$data['code']}%");
        }
        $vouchers = $vouchers->paginate(10);
        return view('admin.voucher.index', compact('vouchers'));
    }

    private function sendMailToUser($userVoucherSendMailMap) {}
}
