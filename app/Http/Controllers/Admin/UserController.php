<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use App\Models\Group;
use App\Models\Module;
use App\Repositories\Hotel\HotelRepository;
use App\Repositories\User\UserRepository;
use App\Services\impl\UserServiceImpl;
use Carbon\Carbon;
use App\Models\District;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    private $userServices;
    private $userRepository;
    private $hotelRepository;

    public function __construct(
        UserServiceImpl $userServices,
        UserRepository  $userRepository,
        HotelRepository $hotelRepository,
    )
    {
        $this->userServices = $userServices;
        $this->userRepository = $userRepository;
        $this->hotelRepository = $hotelRepository;
    }

    public function index(Request $request)
    {
        $users = $this->userRepository->getAll($request);
        return view('admin.users.index', compact('users'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $hotels = $this->hotelRepository->getAllForAdmin();
        return view('admin.users.create', compact('hotels'));
    }


    public function store(UserRequest $request)
    {
        $this->userServices->create($request);
        return redirect()->route('admin.users.index')->with('success', 'Thao tác  thành công!');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $hotels = $this->hotelRepository->getAllForAdmin();
        $user = $this->userRepository->find($id);
        return view('admin.users.edit', compact('user', 'hotels'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $this->userServices->update($id, $request);
        return redirect()->route('admin.users.index')->with('success', 'Thao tác thành công!');;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->userRepository->find($id);
        $this->userRepository->delete($data);

        return redirect()->back()->with('success', 'Thao tác  thành công!');;
    }

    public function permissionsList()
    {
        $groups = Group::all();
        return view('admin.permissions.index', compact("groups"));
    }
    public function permissionsEdit($id)
    {
        $group = Group::query()->find($id);
        $modules = Module::all();
        return view('admin.permissions.edit', compact("group",'modules'));
    }
    public function permissionsUpdate(Request $request, $id)
    {
        $group = Group::query()->findOrFail($id);

        // Các quyền mặc định
        $defaultPermissions = [
            "view_overview",
            "create_overview",
            "edit_overview",
            "delete_overview",
        ];

        // Hợp nhất các quyền mặc định và quyền từ request, loại bỏ key
        $permissions = array_unique(array_merge($defaultPermissions, $request->permissions ?? []));
        $permissions = array_values($permissions); // Loại bỏ key

        // Lưu permissions dưới dạng mảng tuần tự
        $group->permissions = json_encode($permissions);
        $group->save();

        return redirect()->route('admin.permissions.edit', $id)->with('success', 'Thao tác thành công!');
    }
}
