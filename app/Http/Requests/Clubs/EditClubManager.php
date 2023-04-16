<?php

namespace App\Http\Requests\Clubs;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\LocalResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Hash;

class EditClubManager extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'manager_id' => 'required|exists:users,id',
        ];
    }
    public function messages()
    {
        return [
            'manager_id.required' => 'Manager ID is required',
            'manager_id.exists' => 'Manager ID need to be exists',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(LocalResponse::returnError('Error on editing Manager', 401, $validator->errors()));
    }
    public function values($manager)
    {
        return [
            "name" => isset($this->name) ? $this->name : $manager->name,
            "password" => isset($this->password) ? Hash::make($this->password) : $manager->password,
            "email" => isset($this->email) ? $this->email : $manager->email,
        ];
    }
}
