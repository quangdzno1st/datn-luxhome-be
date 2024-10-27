<?php

namespace App\Http\Requests\Api\Room;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'status' => 'required|integer|in:0,1,2,3'  // Chỉ cho phép các giá trị hợp lệ
        ];
    }
    public function messages()
    {
        return [
            'status.required'=>"Phải chọn trạng thái phòng" 
        ];
    }
}
