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
            'hotel_id' => 'required|string',
            'voucher_id' => 'nullable|string',
            'user_id' => 'nullable|string',
            'user_email' => 'required|email',
            'user_phone_number' => 'required|string|regex:/^[0-9]{10,15}$/',
            'user_name' => 'required|string|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'note' => 'nullable|string',
            'order_items' => 'required|array',
            'order_items.*.room_id' => 'required|string',
            'order_items.*.room_code' => 'required|string',
            'order_items.*.services' => 'nullable|array',
            'order_items.*.services.*.service_id' => 'required_with:order_items.*.services.*|string',
            'order_items.*.services.*.service_name' => 'required_with:order_items.*.services.*|string',
            'order_items.*.services.*.service_quantity' => 'required_with:order_items.*.services.*|integer|min:1',
            ''
        ];
    }

    public function messages()
    {
        return [
            'order_items.required' => 'Danh sách order items là bắt buộc.',
            'order_items.*.room_id.required' => 'Room ID là bắt buộc.',
            'order_items.*.room_id.uuid' => 'Room ID phải là định dạng UUID hợp lệ.',
            'order_items.*.room_code.required' => 'Room Code là bắt buộc.',
            'order_items.*.services.required' => 'Danh sách dịch vụ là bắt buộc.',
            'order_items.*.services.*.service_id.required' => 'Service ID là bắt buộc.',
            'order_items.*.services.*.service_id.uuid' => 'Service ID phải là định dạng UUID hợp lệ.',
            'order_items.*.services.*.service_name.required' => 'Tên dịch vụ là bắt buộc.',
            'order_items.*.services.*.service_quantity.required' => 'Số lượng dịch vụ là bắt buộc.',
            'order_items.*.services.*.service_quantity.integer' => 'Số lượng dịch vụ phải là một số nguyên.',
            'order_items.*.services.*.service_quantity.min' => 'Số lượng dịch vụ phải lớn hơn hoặc bằng 1.',
        ];
    }
}
