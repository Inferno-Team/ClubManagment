<?php

namespace App\Http\Requests\clubs;

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
            'image' => 'file',
            'manager' => 'required',
            // 'manager.name' => 'required',
            // 'manager.password' => 'required',
            // 'manager.email' => 'required|email|unique:users,email'
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
        $manager = json_decode($this->input('manager'), true);
        return [
            'name' => $manager['name'],
            'email' => $manager['email'],
            'password' => Hash::make($manager['password']),
            'type' => 'manager',
        ];
    }
}
