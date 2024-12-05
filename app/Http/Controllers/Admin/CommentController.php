<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required'
        ], ['content.required' => 'Bạn chưa nhập nội dung bình luận']);

        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->with('error', 'Bình luận không thành công!');
        }

        $data = $request->all();

        $comment = Comment::query()->create($data);

        return back()->with('success', 'Bình luận thành công!');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required'
        ], ['content.required' => 'Bạn chưa nhập nội dung bình luận']);

        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->with('error', 'Cập nhật bình luận không thành công!');
        }

        $data = $request->all();

        $comment = Comment::query()->where('id', $id)->firstOrFail();

        $comment->update([
            'content' => $data['content']
        ]);

        return back()->with('success', 'Cập nhật bình luận thành công!');
    }

    public function delete($id)
    {
        $comment = Comment::query()->where('id', $id)->firstOrFail();

        $comment->delete();

        return back()->with('success', 'Xóa bình luận thành công!');
    }
}
