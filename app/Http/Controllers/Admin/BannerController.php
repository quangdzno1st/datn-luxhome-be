<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    const PATH_VIEW = 'admin.banners.';

    public function index()
    {
        $query = Banner::query();

        if (request()->has('type') && !empty(request()->input('type'))) {
            $type = request()->input('type');
            $query->where('status', $type);
        }

        $banners = $query->latest('created_at')->paginate(10);

        return view(self::PATH_VIEW . __FUNCTION__, compact('banners'));
    }

    public function create()
    {
        return view(self::PATH_VIEW . __FUNCTION__);
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'image' => 'required|mimes:jpeg,png,jpg,gif',
        ], [
            'image.required' => 'Bạn chưa chọn ảnh.',
            'image.mimes' => 'Ảnh không đúng định dạng'
        ]);

        $data = $request->all();

        if (empty($data['status'])) {
            $data['status'] = 2;
        }

        try {
            DB::beginTransaction();

            $data['image'] = Storage::put('banners', $data['image']);

            $banner = Banner::query()->create($data);

            DB::commit();

            return redirect()->route('admin.banners.index')->with('success', 'Thêm banner thành công!');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request ,$id)
    {
        $banner = Banner::query()->where('id', $id)->firstOrFail();

        if ($banner->status == 1) {
            $banner->update(['status' => 2]);
            return back()->with('success', 'Ẩn banner thành công');
        }
        $banner->update(['status' => 1]);
            return back()->with('success', 'Hiển thị banner thành công');
    }

    public function destroy($id)
    {
        $banner = Banner::query()->where('id', $id)->firstOrFail();

        $banner->delete();

        if ($banner->image && Storage::exists($banner->image)) {
            Storage::delete($banner->image);
        }

        return back()->with('success', 'Xóa banner thành công!');
    }
}
