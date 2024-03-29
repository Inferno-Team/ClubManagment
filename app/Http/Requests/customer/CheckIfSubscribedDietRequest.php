<?php

namespace App\Http\Requests\customer;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\LocalResponse;

class CheckIfSubscribedDietRequest extends FormRequest
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
            "club_id" => "required|exists:eat_tables,id",
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        info($this->club_id);
        throw new HttpResponseException(LocalResponse::returnError(
            'Error in check diet subscription',
            401,
            $validator->errors()
        ));
    }
}
