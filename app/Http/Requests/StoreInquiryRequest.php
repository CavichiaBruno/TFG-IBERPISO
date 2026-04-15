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
            'message' => 'required|string|min:10|max:2000',
        ];

        if (!auth()->check()) {
            $rules['guest_name']  = 'required|string|max:100';
            $rules['guest_email'] = 'required|email|max:150';
            $rules['guest_phone'] = 'nullable|string|max:20';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'guest_name.required'  => 'Tu nombre es obligatorio.',
            'guest_email.required' => 'Tu email es obligatorio.',
            'guest_email.email'    => 'Introduce un email válido.',
            'message.required'     => 'El mensaje es obligatorio.',
            'message.min'          => 'El mensaje debe tener al menos 10 caracteres.',
        ];
    }
}
