<?php

namespace App\Http\Requests\Api\Voucher;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class CreateVoucherRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif',
            'description' => 'required|string|max:1000', // có thể bỏ trống, là chuỗi, không quá 1000 ký tự
            'status' => 'required|in:1,0', // bắt buộc, phải là một trong hai giá trị: 'active' hoặc 'inactive'
            'quantity' => 'required|integer|min:0', // bắt buộc, là số nguyên, tối thiểu là 1
            'discount_value' => 'required|numeric|min:0', // bắt buộc, là số, tối thiểu là 1
            'start_date' => 'nullable|date|after_or_equal:today', // bắt buộc, phải là ngày hợp lệ
            'end_date' => 'nullable|date|after_or_equal:start_date', // có thể bỏ trống, là ngày hợp lệ, phải lớn hơn hoặc bằng ngày bắt đầu
            'discount_type' => 'required|in:1,0',
            'max_price' => 'numeric|min:0',
            'conditional_total_amount' => 'numeric|min:1'
        ];
    }
    public function messages()
    {
        return [
//            'code.required' => 'Mã voucher là bắt buộc',
//            'code.string' => 'Mã voucher phải là một chuỗi ký tự',
//            'code.max' => 'Mã voucher không được vượt quá 255 ký tự',
//            'code.unique' => 'Mã voucher đã tồn tại, vui lòng chọn mã khác',
            'thumbnail.required' => 'Vui lòng nhập ảnh đại diện',

            'description.string' => 'Mô tả phải là một chuỗi ký tự',
            'description.required' => 'Mô tả phải là bắt buộc',
            'description.max' => 'Mô tả không được vượt quá 1000 ký tự',

            'status.required' => 'Trạng thái là bắt buộc',
            'status.in' => 'Trạng thái không hợp lệ, chỉ chấp nhận active hoặc inactive',

            'quantity.required' => 'Số lượng là bắt buộc',
            'quantity.integer' => 'Số lượng phải là một số nguyên',
            'quantity.min' => 'Số lượng phải lớn hơn hoặc bằng 0',

            'discount_type.required' => 'Loại giảm giá là bắt buộc',
            'discount_type.in' => 'Loại giảm giá không hợp lệ, chỉ chấp nhận percent hoặc fixed',

            'discount_value.required' => 'Giá trị giảm giá là bắt buộc',
            'discount_value.numeric' => 'Giá trị giảm giá phải là một số',
            'discount_value.min' => 'Giá trị giảm giá phải lớn hơn hoặc bằng 1',

            'start_date.date' => 'Ngày bắt đầu phải là ngày hợp lệ',
            'start_date.after_or_equal' => 'Ngày bắt đầu phải lớn hơn hoặc bằng ngày hiện tại.',

            'end_date.date' => 'Ngày kết thúc phải là ngày hợp lệ',
            'end_date.after_or_equal' => 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu',

            'min_price.numeric' => 'Giá tối thiểu phải là một số',
            'min_price.min' => 'Giá tối thiểu phải lớn hơn hoặc bằng 0',

            'max_price.numeric' => 'Giá tối đa phải là một số',
            'max_price.gte' => 'Giá tối đa phải lớn hơn hoặc bằng giá tối thiểu',
            'max_price.min' => 'Giá giảm tối đa phải lớn hơn hoặc bằng 0',

            'rank_id.integer' => 'Rank phải là số nguyên',
            'rank_id.exists' => 'Rank không tồn tại trong hệ thống',

            'conditional_rank.boolean' => 'Điều kiện rank phải là giá trị boolean',

            'conditional_total_amount.numeric' => 'Tổng số tiền điều kiện phải là một số',
            'conditional_total_amount.min' => 'Tổng số tiền điều kiện phải lớn hơn hoặc bằng 1',
        ];
    }

//    protected function failedValidation(Validator $validator)
//    {
//        $json = [
//            'status' => false,
//            'message' => $validator->errors()->first()
//        ];
//        $response = response( $json, 422 );
//        throw (new ValidationException($validator, $response))->status(422);
//    }

}
