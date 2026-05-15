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

        $response = $this->mistralService->chat($userMessage);

        return response()->json([
            'response' => $response
        ]);
    }
}
