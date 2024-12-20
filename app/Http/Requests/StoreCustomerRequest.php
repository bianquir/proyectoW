<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
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
            'dni' => 'required|integer',
            'cuil' => 'required|integer',
            'name' => 'required|string|max:250',
            'lastname' => 'string|max:250',
            'phone_number' => 'required|integer',
            'email' => 'email',
            'address' => 'string|max:50',
            'tag_id' => 'required|integer|exists:tags,id'
        ];
    }
}
