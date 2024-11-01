<?php

namespace App\Http\Requests\Api\Hotel;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHotelRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'location' => 'required|max:255',
            'quantity_of_room' => 'required|numeric|min:1',
            'star' => 'required|numeric|min:1',
            'city_id' => 'required|exists:cities,id',
            'phone' => 'required|numeric',
            'email' => 'required|email',
            'quantity_floor' => 'required|numeric|min:1'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên khách sạn đang trống',
            'name.max' => 'Tên khách sạn có tối đa 255 ký tự',
            'location.required' => 'Địa chỉ đang trống',
            'location.max' => 'Địa chỉ có tối đa 255 ký tự',
            'quantity_of_room.required' => 'Số lượng phòng đang trống',
            'quantity_of_room.numeric' => 'Số lượng phòng phải là số',
            'quantity_of_room.min' => 'Số lượng phòng phải lớn hơn hoặc bằng 1',
            'star.required' => 'Số sao đang trống',
            'star.numeric' => 'Số sao phải là số',
            'star.min' => 'Số sao phải lớn hơn hoặc bằng 1',
            'city_id.required' => 'Thành phố đang trống',
            'city_id.exists' => 'Thành phố không tồn tại',
            'phone.required' => 'Số điện thoại đang trống',
            'phone.numeric' => 'Số điện thoại phải là số',
            'email.required' => 'Email đang trống',
            'email.email' => 'Email không đúng định dạng',
            'quantity_floor.required' => 'Số tầng đang trống',
            'quantity_floor.numeric' => 'Số tầng phải là số',
            'quantity_floor.min' => 'Số tầng phải lớn hơn hoặc bằng 1'
        ];
    }
}
