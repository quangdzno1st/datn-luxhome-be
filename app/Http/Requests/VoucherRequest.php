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
            'total_amount_ordered_to' => [
                'nullable',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) {
                    $from = $this->input('total_amount_ordered_from');
                    if (!is_null($from) && !is_null($value) && $value < $from) {
                        $fail('Trường "Tổng chi tiêu đến" phải lớn hơn hoặc bằng "Tổng chi tiêu từ".');
                    }
                },
            ],
        ];
    }

    public function messages()
    {
        return [
            'total_amount_ordered_from.numeric' => 'Trường "Tổng chi tiêu từ" phải là một số.',
            'total_amount_ordered_from.min' => 'Trường "Tổng chi tiêu từ" phải lớn hơn hoặc bằng 0.',
            'total_amount_ordered_to.numeric' => 'Trường "Tổng chi tiêu đến" phải là một số.',
            'total_amount_ordered_to.min' => 'Trường "Tổng chi tiêu đến" phải lớn hơn hoặc bằng 0.',
            'total_amount_ordered_to.gte' => 'Trường "Tổng chi tiêu đến" phải lớn hơn hoặc bằng "Tổng chi tiêu từ".',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'total_amount_ordered_from' => $this->filled('total_amount_ordered_from')
                ? (int) str_replace(',', '', $this->total_amount_ordered_from)
                : null,
            'total_amount_ordered_to' => $this->filled('total_amount_ordered_to')
                ? (int) str_replace(',', '', $this->total_amount_ordered_to)
                : null,
        ]);
    }


}
