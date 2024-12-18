<?php

namespace App\Http\Requests\Api\Hotel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class CreateHotelRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'images' => 'required|array',
            'images.*' => 'mimes:jpeg,jpg,png',
            'location' => 'required|max:255',
            'quantity_of_room' => 'required|numeric|min:1|integer',
            'star' => 'required|numeric|min:1|max:5|integer',
            'city_id' => 'required|exists:cities,id',
            'phone' => ['required', 'regex:/^(0[3|5|7|8|9])+([0-9]{8})$/'],
            'email' => 'required|email',
            'quantity_floor' => 'required|numeric|min:1|integer',
            'thumbnail' => 'required|mimes:jpeg,jpg,png',
            'description' => 'nullable',
            'province' => 'required',
            'district' => 'required',
            'commune' => 'required',
            'latitude' => 'required|max:255',
            'longitude' => 'required|max:255',
            'percent_incidental' => 'required|numeric|integer|min:0|max:100'
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
            'star.max' => 'Số sao phải nhỏ hơn hoặc bằng 5',
            'city_id.required' => 'Thành phố đang trống',
            'city_id.exists' => 'Thành phố không tồn tại',
            'phone.required' => 'Số điện thoại đang trống',
            'phone.regex' => 'Số điện thoại phải là không đúng định dạng',
            'email.required' => 'Email đang trống',
            'email.email' => 'Email không đúng định dạng',
            'quantity_floor.required' => 'Số tầng đang trống',
            'quantity_floor.numeric' => 'Số tầng phải là số',
            'quantity_floor.min' => 'Số tầng phải lớn hơn hoặc bằng 1',
            'images.required' => 'Bạn chưa chọn ảnh cho khách sạn',
            'images.*.mimes' => 'Hình ảnh phải có định dạng jpeg, jpg hoặc png.',
            'thumbnail.mimes' => 'Ảnh không đúng định dạng',
            'thumbnail.required' => 'Bạn chưa chọn ảnh đại diện cho khách sạn',
            'province.required' => 'Tỉnh đang trống',
            'district.required' => 'Quận đang trống',
            'commune.required' => 'Xã đang trống',
            'latitude.required' => 'Vĩ độ đang trống',
            'latitude.max' => 'Vĩ độ quá dài',
            'longitude.required' => 'Kinh độ đang trống',
            'longitude.max' => 'Kinh độ quá dài',
            'quantity_of_room.integer' => 'Số phòng phải là số nguyên',
            'star.integer' => 'Số sao phải là số nguyên',
            'quantity_floor.integer' => 'Số tầng phải là số nguyên',
            'percent_incidental.required' => 'Phần trăm tiền phạt bắt buộc',
            'percent_incidental.numeric' => 'Phần trăm tiền phạt phải là 1 số',
            'percent_incidental.integer' => 'Phần trăm tiền phạt phải là số nguyên',
            'percent_incidental.min' => 'Phần trăm tiền phạt lớn hơn hoặc bằng :min',
            'percent_incidental.max' => 'Phần trăm tiền phạt nhỏ hơn hoặc bằng :max'
        ];
    }
    
    protected function failedValidation(Validator $validator)
    {
        session()->flash('error', 'Thêm khách sạn không thành công.');

        parent::failedValidation($validator);
    }
}
