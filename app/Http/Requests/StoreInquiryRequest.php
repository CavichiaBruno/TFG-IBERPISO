<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'mensaje' => 'required|string|min:10|max:2000',
        ];

        if (!auth()->check()) {
            $rules['nombre_visitante']  = 'required|string|max:100';
            $rules['correo_visitante'] = 'required|email|max:150';
            $rules['telefono_visitante'] = 'nullable|string|max:20';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'nombre_visitante.required'  => 'Tu nombre es obligatorio.',
            'correo_visitante.required' => 'Tu email es obligatorio.',
            'correo_visitante.email'    => 'Introduce un email válido.',
            'mensaje.required'     => 'El mensaje es obligatorio.',
            'mensaje.min'          => 'El mensaje debe tener al menos 10 caracteres.',
        ];
    }
}
