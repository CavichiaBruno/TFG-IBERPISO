<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\Property;
use App\Http\Requests\StoreInquiryRequest;

class InquiryController extends Controller
{
    public function store(StoreInquiryRequest $request, int $id)
    {
        $property = Property::active()->findOrFail($id);

        $data = $request->validated();
        $data['propiedad_id'] = $property->id;

        if (auth()->check()) {
            $data['usuario_id'] = auth()->id();
            $data['nombre_visitante'] = auth()->user()->nombre;
            $data['correo_visitante'] = auth()->user()->correo;
            $data['telefono_visitante'] = auth()->user()->telefono;
        }

        Inquiry::create($data);

        return response()->json([
            'success' => true,
            'message' => '¡Consulta enviada! El propietario ha sido notificado y se pondrá en contacto contigo pronto.',
        ]);
    }
}
