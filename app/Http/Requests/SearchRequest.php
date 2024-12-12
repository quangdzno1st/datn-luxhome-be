<?php

namespace App\Http\Requests;

use Carbon\Carbon;
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
        $isTypeTrue = $this->input('type');
        return [
            'number_adult' => $isTypeTrue ? 'nullable|integer|min:1' : 'required|integer|min:1',
            'number_child' => 'nullable|integer|min:1',

            'start_date' => [
                'sometimes',
                'required',
                'date_format:Y-m-d',
                'after_or_equal:' . Carbon::now()->format('Y-m-d'),
                'before_or_equal:end_date',
            ],

            'end_date' => [
                'sometimes',
                'required',
                'date_format:Y-m-d',
                'after:start_date', // Dùng 'after' thay vì 'after_or_equal' để end_date phải lớn hơn start_date
                'after:' . Carbon::now()->format('Y-m-d'),
            ],


            'city_id' => $this->isHomePage() ? 'sometimes|required|exists:cities,id' : 'nullable',
        ];

    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu và ngày hiện tại.',
            'number_adult.required' => 'Người lớn là bắt buộc.',
            'number_adult.integer' => 'Người lớn phải là số.',
            'number_adult.min' => 'Người lớn phải ít nhất là 1.',
            'number_child.integer' => 'Trẻ em phải là số.',
            'number_child.min' => 'Trẻ em phải ít nhất là 1.',
            'start_date.required' => 'Ngày bắt đầu là bắt buộc.',
            'start_date.date' => 'Ngày bắt đầu không đúng định dạng.',
            'end_date.required' => 'Ngày kết thúc là bắt buộc.',
            'end_date.date' => 'Ngày kết thúc không đúng định dạng.',
            'city_id.required' => 'Thành phố là bắt buộc.',
            'city_id.integer' => 'Thành phố không đúng định dạng.',
            'city_id.exists' => 'Thành phố không tồn tại.',
            'start_date.date_format' => 'Sai định dạng yyyy/mm/dd.',
            'end_date.date_format' => 'Sai định dạng yyyy/mm/dd.',
            'start_date.after_or_equal' => 'Ngày bắt đầu phải là hôm nay hoặc sau đó.',
            'start_date.before_or_equal' => 'Ngày bắt đầu trước ngày kết thúc.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải là hôm nay hoặc sau đó.',
            'end_date.after_or_equal.start_date' => 'Ngày kết thúc sau ngày bắt đầu.',
        ];
    }

    private function isHomePage()
    {
        return request()->is('/');
    }
}
