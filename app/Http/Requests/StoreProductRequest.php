<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:250',
            'description' => 'string|max:250',
            'price' => 'required|numeric',
        ];
    }


    public function messages(): array
    {
        return [
            'name' => 'El nombre es obligatorio',
            'description' => 'La descripción solo debe contener letras o simbolos',
            'price' => 'El precio es obligatorio',
        ];
    }


}
