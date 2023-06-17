<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompraEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'MatQxAdq' => 'required',
            // Logic for findOrFail
            // 'username' => 'unique:users,username,'.$this->user.'|required',

        ];
    }
}
