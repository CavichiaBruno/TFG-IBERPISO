<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'titulo'              => 'required|string|max:200',
            'descripcion'        => 'required|string|min:100',
            'precio'              => 'required|numeric|min:0',
            'superficie_m2'         => 'required|numeric|min:0',
            'habitaciones'              => 'required|integer|min:0|max:100',
            'banos'          => 'required|integer|min:0|max:50',
            'piso'              => 'nullable|integer',
            'tipo_propiedad'      => 'required|in:piso,casa,chalet,local,garaje,oficina',
            'tipo_operacion'     => 'required|in:venta,alquiler',
            'direccion'            => 'required|string|max:255',
            'ciudad'               => 'required|string|max:100',
            'provincia'           => 'required|string|max:100',
            'codigo_postal'        => 'required|string|size:5',
            'latitud'           => 'nullable|numeric|between:-90,90',
            'longitud'          => 'nullable|numeric|between:-180,180',
            'certificado_energetico' => 'nullable|in:A,B,C,D,E,F,G',
            'url_tour_virtual'   => 'nullable|url|max:500',
            'certificado_energetico_archivo' => 'nullable|file|mimes:pdf|max:5120',
        ];
    }
}
