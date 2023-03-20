<?php

namespace App\Http\Requests\clubs;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\LocalResponse;

class MakeClubSubscrpitionRequest extends FormRequest
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
            "subscription_id" => ["required", "exists:subscription_types,id"],
            "price" => "required|numeric",
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(LocalResponse::returnError('Error on deleting club', 401, $validator->errors()));
    }
    public function values()
    {
        return [
            'club_id' => request()->user()->club->id,
            'subscription_id' => $this->subscription_id,
            'price' => $this->price,
        ];
    }
}
