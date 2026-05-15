<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\Property;
use App\Http\Requests\StoreInquiryRequest;

/**
 * Controlador que gestiona el envío de consultas de contacto sobre propiedades.
 *
 * Cuando un usuario hace clic en "Contactar" desde la ficha de una propiedad,
 * este controlador crea el registro de consulta en la base de datos.
 */
class InquiryController extends Controller
{
    /**
     * Guarda una nueva consulta de contacto sobre una propiedad.
     *
     * Si el usuario está autenticado, sus datos (nombre, correo, teléfono)
     * se rellenan automáticamente. Si es un visitante anónimo, se usan
     * los datos que escribió en el formulario.
     *
     * Devuelve una respuesta JSON para que el frontend pueda mostrar
     * el mensaje de confirmación sin recargar la página.
     *
     * @param  \App\Http\Requests\StoreInquiryRequest $request
     * @param  int $id ID de la propiedad sobre la que se realiza la consulta
     * @return \Illuminate\Http\JsonResponse
     */
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
