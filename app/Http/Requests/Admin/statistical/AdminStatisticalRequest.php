<?php

namespace App\Http\Requests\Admin\statistical;

use Illuminate\Foundation\Http\FormRequest;

class AdminStatisticalRequest extends FormRequest
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
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'option_time' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'start_date.required' => 'Bạn chưa chọn thời gian bắt đầu',
            'end_date.required' => 'Bạn chưa chọn thời gian kết thúc',
            'end_date.after_or_equal' => 'Thời gian kết thúc phải sau thời gian bắt đầu.',
            'option_time.required' => 'Bạn chưa chọn mốc thời gian'
        ];
    }
}