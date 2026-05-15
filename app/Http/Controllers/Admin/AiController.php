<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MistralAiService;

/**
 * Controlador que expone el endpoint de análisis de imágenes con Inteligencia Artificial.
 *
 * Recibe una imagen desde el panel de administración, la convierte al formato
 * base64 y la envía al servicio de Mistral AI para obtener sugerencias
 * de título, descripción y recomendaciones fotográficas del inmueble.
 */
class AiController extends Controller
{
    /** @var MistralAiService Servicio que comunica con la API de Mistral AI */
    protected MistralAiService $aiService;

    /**
     * Inyecta el servicio de IA al crear el controlador.
     *
     * @param  \App\Services\MistralAiService $aiService
     */
    public function __construct(MistralAiService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Analiza una imagen de propiedad y devuelve sugerencias generadas por IA.
     *
     * Pasos que realiza:
     * 1. Valida que se haya subido una imagen de máximo 10 MB.
     * 2. Convierte el archivo a formato base64 (necesario para enviarlo a la API).
     * 3. Llama al servicio de Mistral AI con la imagen.
     * 4. Devuelve el resultado en JSON (título, descripción y recomendaciones).
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function analyzeImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:10240', // máx 10MB
        ]);

        $imageFile = $request->file('image');

        // Convertir a base64
        $imageData = base64_encode(file_get_contents($imageFile->getRealPath()));

        $result = $this->aiService->analyzePropertyImage($imageData);

        if ($result) {
            return response()->json($result);
        }

        return response()->json([
            'error' => 'No se pudo analizar la imagen. Por favor, revisa los logs.'
        ], 500);
    }
}
