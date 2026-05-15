<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'titulo'              => 'sometimes|required|string|max:200',
            'descripcion'        => 'sometimes|required|string|min:100',
            'precio'              => 'sometimes|required|numeric|min:0',
            'superficie_m2'         => 'sometimes|required|numeric|min:0',
            'habitaciones'              => 'sometimes|required|integer|min:0|max:100',
            'banos'          => 'sometimes|required|integer|min:0|max:50',
            'piso'              => 'nullable|integer',
            'tipo_propiedad'      => 'sometimes|required|in:piso,casa,chalet,local,garaje,oficina',
            'tipo_operacion'     => 'sometimes|required|in:venta,alquiler',
            'direccion'            => 'sometimes|required|string|max:255',
            'ciudad'               => 'sometimes|required|string|max:100',
            'provincia'           => 'sometimes|required|string|max:100',
            'codigo_postal'        => 'sometimes|required|string|size:5',
            'latitud'           => 'nullable|numeric|between:-90,90',
            'longitud'          => 'nullable|numeric|between:-180,180',
            'certificado_energetico' => 'nullable|in:A,B,C,D,E,F,G',
            'url_tour_virtual'   => 'nullable|url|max:500',
            'certificado_energetico_archivo' => 'nullable|file|mimes:pdf|max:5120',
            'eliminar_certificado' => 'nullable|boolean',
        ];
    }
}
