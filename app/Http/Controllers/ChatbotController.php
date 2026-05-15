<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MistralAiService;

/**
 * Controlador que gestiona el Asistente Virtual (Chatbot) de IberPiso.
 *
 * Recibe los mensajes del usuario, los envía al servicio de Inteligencia
 * Artificial (Mistral AI) y devuelve la respuesta con sugerencias de
 * propiedades en formato JSON para que el frontend las muestre.
 */
class ChatbotController extends Controller
{
    /** @var MistralAiService Servicio que comunica con la API de Mistral AI */
    protected MistralAiService $mistralService;

    /**
     * Inyecta el servicio de IA al crear el controlador.
     *
     * Laravel resuelve automáticamente la dependencia gracias al contenedor de servicios.
     *
     * @param  \App\Services\MistralAiService $mistralService
     */
    public function __construct(MistralAiService $mistralService)
    {
        $this->mistralService = $mistralService;
    }

    /**
     * Muestra la página del chatbot con el interfaz de conversación.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('chatbot.index');
    }

    /**
     * Procesa el mensaje del usuario y devuelve la respuesta del asistente.
     *
     * Valida que el mensaje no supere los 500 caracteres y lo envía
     * al servicio de Mistral AI. La respuesta incluye un mensaje de texto
     * y, opcionalmente, una lista de propiedades recomendadas.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500'
        ]);

        $userMessage = $request->input('message');
        \Log::info('Chatbot: Mensaje recibido: ' . $userMessage);

        $response = $this->mistralService->chat($userMessage);
        \Log::info('Chatbot: Respuesta de Mistral completa: ' . $response);

        // Limpieza agresiva de caracteres que puedan romper el JSON
        $response = preg_replace('/[\x00-\x1F\x7F]/', '', $response); // Elimina caracteres de control
        $response = trim($response);

        // Intentamos decodificar
        $decoded = json_decode($response, true);

        // Si falla el primer decode, intentamos limpiar posibles marcas de markdown
        if (json_last_error() !== JSON_ERROR_NONE) {
            \Log::warning('Chatbot: Error al decodificar JSON inicial: ' . json_last_error_msg());
            $cleanResponse = preg_replace('/^```json\s*|```$/m', '', $response);
            $decoded = json_decode($cleanResponse, true);
        }

        $finalMessage = is_array($decoded) && isset($decoded['message']) 
            ? $decoded['message'] 
            : $response;

        // Buscamos propiedades en inglés o español
        $props = [];
        if (is_array($decoded)) {
            if (isset($decoded['properties'])) $props = $decoded['properties'];
            elseif (isset($decoded['propiedades'])) $props = $decoded['propiedades'];
        }

        // Normalizamos el texto a UTF-8 limpio para evitar errores de codificación en la respuesta JSON
        $toUtf8 = fn($s) => mb_convert_encoding((string)($s ?? ''), 'UTF-8', 'UTF-8');

        $cleanProps = [];
        foreach ($props as $p) {
            $cleanProps[] = [
                'title'   => $toUtf8($p['title'] ?? ''),
                'price'   => $toUtf8($p['price'] ?? ''),
                'image'   => $toUtf8($p['image'] ?? ''),
                'url'     => $toUtf8($p['url'] ?? ''),
                'details' => $toUtf8($p['details'] ?? ''),
            ];
        }

        \Log::info('Chatbot: Propiedades enviadas al front: ' . count($cleanProps));

        return response()->json([
            'response'   => $toUtf8($finalMessage),
            'properties' => $cleanProps,
        ]);
    }
}
