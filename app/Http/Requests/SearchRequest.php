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
            'number_adult' => 'sometimes|required|integer|min:1',
            'start_date' => 'sometimes|required|date_format:Y-m-d|before_or_equal:end_date', // Định dạng ngày: d//m/dyyyy
            'end_date' => 'sometimes|required|date_format:Y-m-d|after_or_equal:start_date', // Định dạng ngày: d//m/dyyyy
// Bắt buộc, định dạng ngày, sau hoặc bằng `start_date`
            'city_id' => $this->isHomePage() ?'sometimes|required|exists:cities,id' :  'nullable',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'number_adult.required' => 'Người lớn là bắt buộc.',
            'number_adult.integer'  => 'Người lớn phải là số.',
            'number_adult.min'      => 'Người lớn phải ít nhất là 1.',
            'start_date.required'   => 'Ngày bắt đầu là bắt buộc.',
            'start_date.date'       => 'Ngày bắt đầu không đúng định dạng.',
            'start_date.before_or_equal' => 'Ngày bắt đầu phải trước hoặc bằng ngày kết thúc.',
            'end_date.required'     => 'Ngày kết thúc là bắt buộc.',
            'end_date.date'         => 'Ngày kết thúc không đúng định dạng.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
            'city_id.required'      => 'Thành phố là bắt buộc.',
            'city_id.integer'       => 'Thành phố không đúng định dạng.',
            'city_id.exists'        => 'Thành phố không tồn tại.',
            'start_date.date_format' => 'Ngày bắt đầu phải có định dạng yyyy/mm/dd.',
            'end_date.date_format' => 'Ngày kết thúc phải có định dạng yyyy/mm/dd.',
        ];
    }

    private function isHomePage()
    {
        return request()->is('/');
    }
}
