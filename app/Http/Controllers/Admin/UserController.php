<?php

namespace App\Http\Controllers\Admin;

use App\Services\impl\UserServiceImpl;
use Carbon\Carbon;
use App\Models\District;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    private $userServices;

    public function __construct(
        UserServiceImpl         $userServices,
    )
    {
        $this->userServices = $userServices;
    }
    public function index(Request $request)
    {
        $users = $this->userServices->index($request);
        return view('admin.content.customer.list', compact('users'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.content.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|unique:users|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'email' => 'required|email',
            'avatar' => ['required', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Điều kiện cho ảnh
        ], [
            'name.required' => "Vui lòng nhập tiêu đề!", // Thông báo khi thiếu tên
            'name.max' => "Tên không được vượt quá 255 ký tự!", // Thông báo khi tên quá dài
            'phone.required' => "Vui lòng nhập số điện thoại!", // Thông báo khi thiếu số điện thoại
            'phone.regex' => "Số điện thoại không hợp lệ!", // Thông báo khi số điện thoại không hợp lệ
            'phone.min' => "Số điện thoại phải có ít nhất 10 chữ số!", // Thông báo khi số điện thoại quá ngắn
            'email.required' => "Vui lòng nhập email!", // Thông báo khi thiếu email
            'email.email' => "Email không hợp lệ!", // Thông báo khi email không hợp lệ
            'avatar.required' => "Vui lòng chọn hình ảnh!", // Thông báo khi thiếu hình ảnh
            'avatar.avatar' => "Tệp tải lên phải là hình ảnh!", // Thông báo khi không phải hình ảnh
            'avatar.mimes' => "Hình ảnh phải có định dạng jpeg, png, jpg hoặc gif!", // Thông báo về định dạng ảnh
            'avatar.max' => "Kích thước hình ảnh không được vượt quá 2MB!" // Thông báo khi ảnh vượt quá kích thước
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        if ($request->hasFile('avatar')) {
            $image = $request->file('avatar');
            $filename = $image->hashName();
            $path = '/file/team/';
            $image->move(public_path('storage' . $path), $filename);
            $data['avatar'] = $path . $filename;
        }
        $this->userServices->create($data);
        return redirect()->route('admin.users.index')->with(['notice' => 'Thêm mới thành công', 'style' => 'success']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = $this->userServices->find($id);
        return view('admin.content.user.edit',compact('user'));
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
        $user = $this->userServices->find($id);
        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:users,phone,'.$id,
            'email' => 'required|email',
        ];

        if (!$user->avatar) {
            $rules['avatar'] = ['required'];
        } else {
            $rules['avatar'] = ['nullable'];
        }

        $validator = Validator::make($request->all(), $rules, [
            'name.required' => "Vui lòng nhập tiêu đề!", // Thông báo khi thiếu tên
            'name.max' => "Tên không được vượt quá 255 ký tự!", // Thông báo khi tên quá dài
            'phone.required' => "Vui lòng nhập số điện thoại!", // Thông báo khi thiếu số điện thoại
            'phone.regex' => "Số điện thoại không hợp lệ!", // Thông báo khi số điện thoại không hợp lệ
            'phone.min' => "Số điện thoại phải có ít nhất 10 chữ số!", // Thông báo khi số điện thoại quá ngắn
            'email.required' => "Vui lòng nhập email!", // Thông báo khi thiếu email
            'email.email' => "Email không hợp lệ!", // Thông báo khi email không hợp lệ
            'avatar.required' => "Vui lòng chọn hình ảnh!", // Thông báo khi thiếu hình ảnh
            'avatar.avatar' => "Tệp tải lên phải là hình ảnh!", // Thông báo khi không phải hình ảnh
            'avatar.mimes' => "Hình ảnh phải có định dạng jpeg, png, jpg hoặc gif!", // Thông báo về định dạng ảnh
            'avatar.max' => "Kích thước hình ảnh không được vượt quá 2MB!" // Thông báo khi ảnh vượt quá kích thước
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->except('_token','_method');

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                remove_link($user->avatar);
            }
            $image = $request->file('avatar');
            $filename = $image->hashName();
            $path = '/file/team/';
            $image->move(public_path('storage' . $path), $filename);
            $data['avatar'] = $path . $filename;
        }
        else {
            $data['avatar'] = $user->avatar;
        }

        $this->userServices->edit($user, $data);
        return redirect()->route('admin.users.index')->with(['notice'=>'Cập nhật thành công','style'=>'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->userServices->find($id);
        $this->userServices->delete($data);
        return redirect()->back()->with(['notice' => 'Xóa thành công', 'style' => 'success']);
    }
}
