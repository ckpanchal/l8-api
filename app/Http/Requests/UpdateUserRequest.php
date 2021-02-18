<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use JWTAuth;

class UpdateUserRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        $userId = JWTAuth::user()->hasRole('Admin') ? $this->id : JWTAuth::user()->id;
        return [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users,email,'.$userId,
            'username' => 'required|string|max:50|unique:users,username,'.$userId,
            'contact_no' => 'required|digits:10'
        ];
    }
}
