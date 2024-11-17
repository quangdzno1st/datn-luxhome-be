<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Đặt true để cho phép request được sử dụng.
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'number_adult' => 'required|integer|min:1', // Bắt buộc, phải là số nguyên, ít nhất là 1
            'start_date'   => 'required|date|before_or_equal:end_date', // Bắt buộc, định dạng ngày, trước hoặc bằng `end_date`
            'end_date'     => 'required|date|after_or_equal:start_date', // Bắt buộc, định dạng ngày, sau hoặc bằng `start_date`
            'city_id' => $this->isHomePage() ?'required|exists:cities,id' :  'nullable',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'number_adult.required' => 'Số lượng người lớn là bắt buộc.',
            'number_adult.integer'  => 'Số lượng người lớn phải là số.',
            'number_adult.min'      => 'Số lượng người lớn phải ít nhất là 1.',
            'start_date.required'   => 'Ngày bắt đầu là bắt buộc.',
            'start_date.date'       => 'Ngày bắt đầu không đúng định dạng.',
            'start_date.before_or_equal' => 'Ngày bắt đầu phải trước hoặc bằng ngày kết thúc.',
            'end_date.required'     => 'Ngày kết thúc là bắt buộc.',
            'end_date.date'         => 'Ngày kết thúc không đúng định dạng.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
            'city_id.required'      => 'Thành phố là bắt buộc.',
            'city_id.integer'       => 'Thành phố không đúng định dạng.',
            'city_id.exists'        => 'Thành phố không tồn tại.',
        ];
    }

    private function isHomePage()
    {
        return request()->is('/');
    }
}
