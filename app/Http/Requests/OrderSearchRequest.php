<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderSearchRequest extends BaseSearchRequest
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
            'code' => 'string',
            'start_date' => 'date',
            'end_date' => 'date',
            'total_amount' => 'numeric',
            'status' => 'numeric',
            'hotel_id' => 'string',
            'order_id' => 'string'
        ];
    }

    public function messages()
    {
        return [
            'start_date.date' => 'Vui lòng nhập ngày vào trường này.',
            'end_date.date' => 'Vui lòng nhập ngày vào trường này.',
            'total_amount' => "Vui lòng nhập số.",
            'status' => "Vui lòng nhập số."
        ];
    }
}
