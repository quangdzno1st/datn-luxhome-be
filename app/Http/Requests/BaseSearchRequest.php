<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseSearchRequest extends FormRequest
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
            'page' => 'integer|min:1',
            'perPage' => 'integer|min:1',
            'keyword' => 'string|nullable'
        ];
    }

    public function getPage()
    {
        return $this->input('page', 1);
    }

    public function getPerPage()
    {
        return $this->input('perPage', 10);
    }

    public function getKeyword()
    {
        return $this->input('keyword', null);
    }
}
