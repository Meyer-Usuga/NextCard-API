<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use phpDocumentor\Reflection\PseudoTypes\True_;

class RoleRequest extends FormRequest
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
            'name' => 'required|max:300|min:5',
            'description' => 'required|max:300|min:10'
        ];
    }
}
