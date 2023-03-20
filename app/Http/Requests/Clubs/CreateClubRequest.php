<?php

namespace App\Http\Requests\Clubs;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\LocalResponse;
use App\Rules\UserValidation;
use Illuminate\Support\Facades\Hash;

class CreateClubRequest extends FormRequest
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
            'name' => 'required|max:255',
            'location' => 'required|max:255',
            'lat' => 'required|max:90',
            'lng' => 'required|max:90',
            'manager' => ['required', new UserValidation],
            'manager.email' => 'email|unique:users,email'
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'location.required' => 'Location is required',
            'lat.required' => 'Lat is required',
            'lng.required' => 'Lng is required',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(LocalResponse::returnError('Error in Create new Club', 401, $validator->errors()));
    }
    public function values($manager_id)
    {
        return [
            'name' => $this->name,
            'location' => $this->location,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'manager_id' => $manager_id
        ];
    }
    public function getManager()
    {
        return [
            'name' => $this->manager['name'],
            'email' => $this->manager['email'],
            'password' => Hash::make($this->manager['password']),
            'type' => 'manager',

        ];
    }
}
