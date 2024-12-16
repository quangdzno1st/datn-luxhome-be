<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VoucherRequest extends FormRequest
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
            'total_amount_ordered_from' => 'nullable|numeric|min:0',
            'total_amount_ordered_to' => 'nullable|numeric|min:0|gte:total_amount_ordered_from',
        ];
    }

    public function messages()
    {
        return [
            'total_amount_ordered_from.numeric' => 'Trường "số tiền từ" phải là một số.',
            'total_amount_ordered_from.min' => 'Trường "số tiền từ" phải lớn hơn hoặc bằng 0.',
            'total_amount_ordered_to.numeric' => 'Trường "số tiền đến" phải là một số.',
            'total_amount_ordered_to.min' => 'Trường "số tiền đến" phải lớn hơn hoặc bằng 0.',
            'total_amount_ordered_to.gte' => 'Trường "số tiền đến" phải lớn hơn hoặc bằng "số tiền từ".',
        ];
    }
}
