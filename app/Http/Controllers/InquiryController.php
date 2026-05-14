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
        }

        Inquiry::create($data);

        return response()->json([
            'success' => true,
            'message' => '\u00a1Consulta enviada correctamente! Nos pondremos en contacto contigo pronto.',
        ]);
    }
}
