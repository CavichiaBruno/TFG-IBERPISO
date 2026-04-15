<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasAdminAccess();
    }

    public function rules(): array
    {
        return [
            'title'              => 'required|string|max:200',
            'description'        => 'required|string|min:100',
            'price'              => 'required|numeric|min:0',
            'surface_m2'         => 'required|numeric|min:0',
            'rooms'              => 'required|integer|min:0|max:20',
            'bathrooms'          => 'required|integer|min:0|max:10',
            'floor'              => 'nullable|integer',
            'property_type'      => 'required|in:piso,casa,chalet,local,garaje,oficina',
            'operation_type'     => 'required|in:venta,alquiler',
            'address'            => 'required|string|max:255',
            'city'               => 'required|string|max:100',
            'province'           => 'required|string|max:100',
            'postal_code'        => 'required|string|size:5',
            'latitude'           => 'nullable|numeric|between:-90,90',
            'longitude'          => 'nullable|numeric|between:-180,180',
            'energy_certificate' => 'nullable|in:A,B,C,D,E,F,G',
            'virtual_tour_url'   => 'nullable|url|max:500',
        ];
    }
}
