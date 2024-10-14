<?php

namespace App\Http\Requests\Api\Voucher;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdateVoucherRequest extends FormRequest
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
            'code' => 'required|string|max:255|unique:vouchers,code', // bắt buộc, là chuỗi, không quá 255 ký tự, phải unique trong bảng vouchers
            'description' => 'required|string|max:1000', // có thể bỏ trống, là chuỗi, không quá 1000 ký tự
            'status' => 'required|in:1,0', // bắt buộc, phải là một trong hai giá trị: 'active' hoặc 'inactive'
            'quantity' => 'required|integer|min:1', // bắt buộc, là số nguyên, tối thiểu là 1
            'discount_type' => 'required|in:1,0', // bắt buộc, phải là 'percent' hoặc 'fixed'
            'discount_value' => 'required|numeric|min:0', // bắt buộc, là số, tối thiểu là 0
            'start_date' => 'nullable|date', // bắt buộc, phải là ngày hợp lệ
            'end_date' => 'nullable|date|after_or_equal:start_date', // có thể bỏ trống, là ngày hợp lệ, phải lớn hơn hoặc bằng ngày bắt đầu
            'min_price' => 'nullable|numeric|min:0', // có thể bỏ trống, là số, tối thiểu là 0
            'max_price' => 'nullable|numeric|gte:min_price', // có thể bỏ trống, là số, phải lớn hơn hoặc bằng min_price
            'rank_id' => 'nullable|integer|exists:ranks,id', // có thể bỏ trống, phải là số nguyên, tồn tại trong bảng ranks
            'conditional_rank' => 'nullable|boolean', // có thể bỏ trống, phải là boolean
            'conditional_total_amount' => 'nullable|numeric|min:0', // có thể bỏ trống, là số, tối thiểu là 0
        ];
    }
    public function messages()
    {
        return [
            'code.required' => 'Mã voucher là bắt buộc',
            'code.string' => 'Mã voucher phải là một chuỗi ký tự',
            'code.max' => 'Mã voucher không được vượt quá 255 ký tự',
            'code.unique' => 'Mã voucher đã tồn tại, vui lòng chọn mã khác',

            'description.string' => 'Mô tả phải là một chuỗi ký tự',
            'description.max' => 'Mô tả không được vượt quá 1000 ký tự',

            'status.required' => 'Trạng thái là bắt buộc',
            'status.in' => 'Trạng thái không hợp lệ, chỉ chấp nhận active hoặc inactive',

            'quantity.required' => 'Số lượng là bắt buộc',
            'quantity.integer' => 'Số lượng phải là một số nguyên',
            'quantity.min' => 'Số lượng phải lớn hơn hoặc bằng 1',

            'discount_type.required' => 'Loại giảm giá là bắt buộc',
            'discount_type.in' => 'Loại giảm giá không hợp lệ, chỉ chấp nhận percent hoặc fixed',

            'discount_value.required' => 'Giá trị giảm giá là bắt buộc',
            'discount_value.numeric' => 'Giá trị giảm giá phải là một số',
            'discount_value.min' => 'Giá trị giảm giá phải lớn hơn hoặc bằng 0',

            'start_date.date' => 'Ngày bắt đầu phải là ngày hợp lệ',

            'end_date.date' => 'Ngày kết thúc phải là ngày hợp lệ',
            'end_date.after_or_equal' => 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu',

            'min_price.numeric' => 'Giá tối thiểu phải là một số',
            'min_price.min' => 'Giá tối thiểu phải lớn hơn hoặc bằng 0',

            'max_price.numeric' => 'Giá tối đa phải là một số',
            'max_price.gte' => 'Giá tối đa phải lớn hơn hoặc bằng giá tối thiểu',

            'rank_id.integer' => 'Rank phải là số nguyên',
            'rank_id.exists' => 'Rank không tồn tại trong hệ thống',

            'conditional_rank.boolean' => 'Điều kiện rank phải là giá trị boolean',

            'conditional_total_amount.numeric' => 'Tổng số tiền điều kiện phải là một số',
            'conditional_total_amount.min' => 'Tổng số tiền điều kiện phải lớn hơn hoặc bằng 0',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $json = [
            'status' => false,
            'message' => $validator->errors()->first()
        ];
        $response = response( $json, 422 );
        throw (new ValidationException($validator, $response))->status(422);
    }
}
