<?php

namespace App\Http\Controllers\Api;

use App\Constant\Enum\RoleEnum;
use App\Http\Requests\Api\Auth\ChangePasswordRequest;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\SignupRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class AuthController extends Controller
{

    public $user;
    public function __construct(
      User $user
    )
    {
        $this->user = $user;
    }


    public function checkPhone(Request $request)
    {

//        $validate = Validator::make($request->all(),
//            ['phone' => ['required', 'regex:/^(03|05|07|08|09)(\d{8})$/', 'unique:users,phone']],
//            [
//                'phone.required' => 'Vui lòng nhập số điện thoại',
//                'phone.regex' => 'Số điện thoại không đúng định dạng',
//                'phone.unique' => 'Số điện thoại đã được đăng ký'
//            ]
//        );

        $user = $this->userRepository->first(['phone' => $request->phone]);
        if ($user) {
            return $this->sendError('Số điện thoại đã được đăng ký', 200);
        }
//        if ($validate->fails()) {
//            return $this->sendError($validate->errors()->first());
//        }
        return $this->sendSuccess([]);
    }

    public function signup(SignupRequest $request)
    {
        try {
            $data = $request->all();
            User::query()->create([
                'id' => (string) Str::uuid(),
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'rank_id' => "fgfdgfdgfdg",
                'type' => User::CUSTOMER,
                'is_active' => User::ACTIVE,
                'address' => $data['address'],
                'org_id' => $data['org_id'],
            ]);

            return response()->json([
                'result' => true,
                'message' => 'Đăng ký tài khoản thành công',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'result' => false,
                'message' => 'Có lỗi xảy ra, vui lòng thử lại sau',
                'error_detail' => $e->getMessage()
            ], 500);
        }
    }

    public function login(LoginRequest $request)
    {
        $user = User::query()->where(['phone' => $request->phone])->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                return $this->loginSuccess($user);
            } else {
                return response()->json([
                    'result' => false,
                    'message' => 'Mật khẩu không chính xác',
                ]);
            }
        } else {
            return response()->json([
                'result' => false,
                'message' => 'Tài khoản không đúng',
            ]);
        }
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = auth()->user();
        if (!Hash::check($request->old_password, $user->password)) {
            return $this->sendError('Mật khẩu hiện tại không đúng');
        }

        if ($request->password != $request->password_confirmation) {
            return $this->sendError('Xác nhận mật khẩu không đúng với mật khẩu trước đó');
        }
        $user->password = Hash::make($request->password);
        $user->save();
        return $this->updateSuccess([]);
    }

    public function resetPassword(Request $request)
    {
        $where['phone']=['phone','=',$request->phone];
        $user = $this->userRepository->first($where);
        if ($user) {
//            $user->verification_code = null;
            if ($request->password_confirmation  != $request->password ) {
                return $this->sendError('Xác nhận mật khảu không đúng');
            }
            $user->password = Hash::make($request->password);
            $user->save();
            return response(['result'=>true,'message'=>'Mật khẩu của bạn đã được thay đổi thành công']);

        } else {
            return $this-> sendError('Không tìm thấy người dùng');
        }
    }

    public function user(Request $request)
    {
        $user = $this->userRepository->finOrFail($request->id);
        return response()->json([
            'result' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'email' => $user->email,
                'avatar' => $user->avatar ? asset_url($user->avatar) : null,
//                'birthday' => $user->birthday?date('d/m/Y', $user->birthday):null,
//                'gender' => ARR_TEXT_GENDER[$user->gender],
                'created_at' => $user->created_at,
//                'address' => $user->address
            ]
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $data = [];


        if (!empty($request->name)) {
            $data['name'] = $request->name;
        }
        if (!empty($request->phone)) {
            $data['phone'] = $request->phone;
        }
        if (!empty($request->birthday)) {
            $time = saveConvertTime($request->birthday);
            $data['birthday'] = $time;
        }
        if (!empty($request->email)) {
            $data['email'] = $request->email;
        }
        if (!empty($request->address)) {
            $data['address'] = $request->address;
        }
        if (!is_null($request->gender)) {
            $data['gender'] = (int)$request->gender;
        }

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            if ($user->avatar) {
                remove_link($user->avatar);
            }
            $datafile = upload_file($file, 'avatar');
            $data['avatar'] = $datafile['link'];
        }

        $user = $this->userRepository->edit($user, $data);
        $user->birthday = date('d/m/Y', $user->birthday);
        return $this->updateSuccess($user);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'result' => true,
            'message' => 'Đăng xuất thành công'
        ]);
    }

    /*public function destroy(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response(['result' => false, 'message' => 'Không tìm thấy thông tin tài khoản']);
        }
        $user->banned = 1;
        $user->save();
        return response([
            'result' => true,
        ]);
    }*/

    protected function loginSuccess($user)
    {
        $token = $user->createToken('API Token')->plainTextToken;
        return response()->json([
            'result' => true,
            'message' => 'Đăng nhập thành công',
            'data' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_at' => null,
                'user' => $user
            ]
        ]);
    }

//    notification
    public function AuthNotification(Request $request)
    {
        $user = auth()->guard('sanctum')->user();

//         $where['user_id']=['user_id','=',$user->id];
        $where['send_group'] = ['send_group', 'orWhere', 1];
        $Notification = $this->notification->paginate($where, ['id' => 'DESC'], [], [], $request->limit ?: 10);
        $Notification->transform(function ($query) use ($user) {
            $query->makeHidden(['created_at', 'updated_at', 'noti_type', 'send_group', 'type', 'content', 'user_ids', 'read_at', 'user_id']);
            $query->date = convert_time(strtotime($query->created_at));

            $userIdsArray = json_decode($query->user_ids, true) ?? [];
            if ($user && in_array($user->id, $userIdsArray)) {
                $query->is_read = true;
            } else {
                $query->is_read = false;
            }
            return $query;
        });
        return $this->sendSuccess($Notification->values());
    }

    //    notification
    public function NotificationDetail($id)
    {
        $user = auth()->guard('sanctum')->user();


        $Notification = $this->notification->finOrFail($id);
//        $this->notification->edit($Notification, ['read_at' => strtotime(Carbon::now())]);
        $Notification->makeHidden('created_at', 'updated_at', 'user_id', 'send_group', 'read_at', 'user_ids');

        $content = json_decode($Notification->content, true);

// Chuyển chuỗi JSON trong trường "data" thành mảng kết hợp trong PHP
//            $data = json_decode($content['data'], true);
// Gán mảng kết hợp vào trường "data" của mảng gốc
//            $content['data'] = $data;
        $Notification->content = $content;
        // Chuyển chuỗi JSON thành mảng trong PHP
        $userIdsArray = json_decode($Notification->user_ids, true) ?? [];

// Kiểm tra xem số 1 có trong mảng không
        if ($user && !in_array($user->id, $userIdsArray)) {
            // Nếu số 1 không tồn tại, thêm nó vào mảng
            $userIdsArray[] = $user->id;
        }

// Chuyển mảng thành chuỗi JSON
        $newUserIdsJson = json_encode($userIdsArray);

        $this->notification->edit($Notification, ['user_ids' => $newUserIdsJson]);

        return $this->sendSuccess($Notification);
    }

// số lượng thông báo chua doc
    function countNotification()
    {
        $id = auth()->user()->id;
        $where['send_group'] = 1;
        $where['user_ids'] = ['user_ids', 'whereJsonDoesntContain', $id];
        $where['and_user'] = ['user_ids', 'orWhere', null];
        $notification = $this->notification->get($where);
        return $this->sendSuccess($notification->count());

    }


    function saveLogUser($data = [])
    {
        $user=auth()->guard('sanctum')->user();
        if ( isset($data['type']) && $data['type'] == 'start') {
            $data['time_start'] = Carbon::now()->timestamp;
            $this->logUserRepository->create($data);
        } else {
//            $where['user_id']=$user->id;
//            $where['device_name']=$request->device_name??$request->header('User-Agent');
            $log = $this->logUserRepository->first(['user_id' => $user->id], ['time_start' => 'DESC']);
            if ($log) {
                $this->logUserRepository->edit($log, ['time_end' => Carbon::now()->timestamp]);
            }
        }
    }


    function timeOut(Request $request)
    {
        $user=auth()->guard('sanctum')->user();

        if ($request->type == 'start') {
            $log = $this->logUserRepository->first(['user_id' => auth()->id()], ['time_start','=',Carbon::now()->timestamp]);
            if(!$log){
                $data = [
                    'user_id' => $user->id??null,
                    'ip_address' => $request->ip(),
                    'device_name' =>$request->device_name??$request->header('User-Agent'),
                    'time_start' => Carbon::now()->timestamp
                ];
                $this->logUserRepository->create($data);
            }
        } else {
            if($user){
                $where['user_id']=$user->id;
                $where['device_name']=$request->device_name??$request->header('User-Agent');
                $log = $this->logUserRepository->first($where, ['time_start' => 'DESC']);
                if ($log) {
                    $this->logUserRepository->edit($log, ['time_end' => Carbon::now()->timestamp]);
                }
            }
        }
        return $this->sendSuccess([]);

    }

}
