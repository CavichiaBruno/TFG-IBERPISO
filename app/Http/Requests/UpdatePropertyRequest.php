<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasAdminAccess();
    }

    public function rules(): array
    {
        return [
            'title'              => 'sometimes|required|string|max:200',
            'description'        => 'sometimes|required|string|min:100',
            'price'              => 'sometimes|required|numeric|min:0',
            'surface_m2'         => 'sometimes|required|numeric|min:0',
            'rooms'              => 'sometimes|required|integer|min:0|max:20',
            'bathrooms'          => 'sometimes|required|integer|min:0|max:10',
            'floor'              => 'nullable|integer',
            'property_type'      => 'sometimes|required|in:piso,casa,chalet,local,garaje,oficina',
            'operation_type'     => 'sometimes|required|in:venta,alquiler',
            'address'            => 'sometimes|required|string|max:255',
            'city'               => 'sometimes|required|string|max:100',
            'province'           => 'sometimes|required|string|max:100',
            'postal_code'        => 'sometimes|required|string|size:5',
            'latitude'           => 'nullable|numeric|between:-90,90',
            'longitude'          => 'nullable|numeric|between:-180,180',
            'energy_certificate' => 'nullable|in:A,B,C,D,E,F,G',
            'virtual_tour_url'   => 'nullable|url|max:500',
        ];
    }
}
