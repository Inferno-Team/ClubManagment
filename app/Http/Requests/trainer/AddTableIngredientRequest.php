<?php

namespace App\Http\Requests\trainer;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\LocalResponse;

class AddTableIngredientRequest extends FormRequest
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
            'ingredient' => 'required|string',
            'quantity' => 'required|string',
            'eat_table_id' => 'required|exists:eat_tables,id'

        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(LocalResponse::returnError(
            'Error in adding Table Ingredient.',
            401,
            $validator->errors()
        ));
    }
    public function values(): array
    {
        return [
            'ingredient' => $this->ingredient,
            'quantity' => $this->quantity,
            'eat_table_id' => $this->eat_table_id,
        ];
    }
}
