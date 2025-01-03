<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupRequest extends FormRequest
{
    /**
     * Permitimos que se realicen peticiones a nuestra entidad
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Definimos las reglas para el request
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'required|max:30|min:3',
            'name' => 'required|max:300|min:3',
            'period' => 'required',
            'status' => 'required'
        ];
    }
}
