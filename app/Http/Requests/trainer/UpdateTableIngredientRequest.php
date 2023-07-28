<?php

namespace App\Http\Requests\trainer;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\LocalResponse;

class UpdateTableIngredientRequest extends FormRequest
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
            'id' => 'required|exists:eat_table_items,id',
            'ingredient' => 'string',
            'quantity' => 'string',

        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(LocalResponse::returnError(
            'Error in updating Table Ingredient.',
            401,
            $validator->errors()
        ));
    }
}
