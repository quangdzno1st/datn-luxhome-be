<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'voucher_id' => 'nullable|string',
            'user_id' => 'nullable|string',
            'user_email' => 'required|email',
            'user_phone_number' => 'required|string|regex:/^[0-9]{10,15}$/',
            'user_name' => 'required|string|max:255',
            'note' => 'nullable|string',
            'total_amount' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'user_name.required' => "Vui lòng nhập tên",
            'user_phone_number.required' => 'Vui lòng nhập số điện thoại',
            'user_phone_number.regex' => 'Vui lòng nhập đúng định dạng số điện thoại',
            'user_email.required' => 'Vui lòng nhập email',
            'user_email.email' => 'Vui lòng nhập đúng định dạng của email',
        ];
    }
}
