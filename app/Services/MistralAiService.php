<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Servicio que gestiona la comunicación con la API de Mistral AI.
 *
 * Encapsula toda la lógica de conexión con la IA para mantener
 * los controladores limpios. Ofrece dos funcionalidades:
 * - Analizar imágenes de propiedades para generar títulos y descripciones.
 * - Responder preguntas del chatbot recomendando propiedades del catálogo.
 */
class MistralAiService
{
    /** @var string Clave de autenticación de la API de Mistral (desde .env) */
    protected string $apiKey;

    /** @var string URL del endpoint de la API de Mistral AI */
    protected string $apiUrl = 'https://api.mistral.ai/v1/chat/completions';

    /**
     * Carga la clave de la API desde la configuración del proyecto.
     * La clave se define en el archivo .env como MISTRAL_API_KEY.
     */
    public function __construct()
    {
        $this->apiKey = config('services.mistral.key', '');
    }

    /**
     * Analiza una imagen de propiedad y genera sugerencias de texto.
     *
     * Envía la imagen (en formato base64) al modelo de visión Pixtral de Mistral.
     * La IA devuelve un título comercial, una descripción publicitaria y
     * recomendaciones para mejorar la fotografía.
     *
     * @param  string $base64Image La imagen codificada en base64
     * @return array|null Array con 'title', 'description' y 'recommendations', o null si falla
     */
    public function analyzePropertyImage(string $base64Image): ?array
    {
        if (empty($this->apiKey)) {
            Log::error('La API Key de Mistral no está configurada.');
            return [
                'title' => 'API Key faltante',
                'description' => 'La API Key de Mistral no está configurada.',
                'recommendations' => 'Por favor, añade MISTRAL_API_KEY en tu archivo .env.'
            ];
        }

        try {
            $response = Http::withoutVerifying()
                ->withToken($this->apiKey)
                ->timeout(30)
                ->post($this->apiUrl, [
                    'model' => 'pixtral-12b-2409',
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => [
                                [
                                    'type' => 'text',
                                    'text' => "Eres un experto copywriter inmobiliario y fotógrafo. Analiza esta imagen de una propiedad.
Devuelve un objeto JSON estrictamente con la siguiente estructura y sin formato markdown:
{
  \"title\": \"Un título corto, comercial y muy atractivo para vender o alquilar\",
  \"description\": \"Una descripción publicitaria persuasiva y vendedora. Enfócate en los beneficios, el estilo de vida y las sensaciones. PROHIBIDO decir 'en esta imagen se ve' o 'se muestra'. Escribe como si fuera un anuncio de lujo publicado en un portal premium.\",
  \"recommendations\": [
    { \"priority\": \"Alta\", \"text\": \"Consejo crítico para mejorar la foto (ej: la foto está oscura, enciende las luces)\" },
    { \"priority\": \"Media\", \"text\": \"Consejo estético (ej: quita los platos de la mesa, añade una planta)\" }
  ]
}
IMPORTANTE: 'title' y 'description' deben ser cadenas de texto simples (strings). 'recommendations' debe ser estrictamente un arreglo de objetos con 'priority' (Alta, Media, Baja) y 'text'."
                                ],
                                [
                                    'type' => 'image_url',
                                    'image_url' => [
                                        'url' => 'data:image/jpeg;base64,' . $base64Image,
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'response_format' => ['type' => 'json_object'],
                    'temperature' => 0.2,
                ]);

            if ($response->successful()) {
                $content = $response->json('choices.0.message.content');
                return json_decode($content, true);
            } else {
                Log::error('Mistral API Error: ' . $response->body());
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Mistral API Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Envía un mensaje del usuario al chatbot y devuelve la respuesta de la IA.
     *
     * Antes de llamar a la API, construye un contexto con las primeras 50 propiedades
     * activas del catálogo para que la IA pueda recomendar inmuebles reales.
     * La respuesta siempre viene en formato JSON con un mensaje y una lista de propiedades.
     *
     * @param  string $message El mensaje escrito por el usuario en el chat
     * @return string La respuesta en formato JSON con "message" y "properties"
     */
    public function chat(string $message): string
    {
        if (empty($this->apiKey)) {
            return 'La API Key de Mistral no está configurada.';
        }

        // Obtiene una lista simple de propiedades disponibles para usar como contexto
        try {
            \Log::info('Mistral: Consultando propiedades para contexto...');
            $properties = \App\Models\Property::active()
                ->with(['medios'])
                ->latest()
                ->limit(50)
                ->get();
            \Log::info('Mistral: Propiedades encontradas: ' . $properties->count());
        } catch (\Exception $e) {
            \Log::error('Mistral: Error al consultar propiedades: ' . $e->getMessage());
            $properties = collect(); // Evitar que rompa si falla la query
        }

        $context = "Lista de propiedades disponibles:\n";
        foreach ($properties as $prop) {
            // Generamos la URL manualmente para asegurar compatibilidad total
            $url = url("/propiedades/{$prop->id}-{$prop->slug}");
            $imgUrl = $prop->cover_url;

            $context .= "ID: {$prop->id} | {$prop->titulo} | {$prop->formatted_price}€ | Enlace: $url | Imagen: $imgUrl\n";
        }

        $systemPrompt = 'Eres un asistente virtual de IberPiso. RESPONDE SIEMPRE EN FORMATO JSON ESTRICTO.
        Estructura JSON requerida:
        {
          "message": "Tu respuesta amigable en texto aquí",
          "properties": [
            {
              "title": "Título comercial corto",
              "price": "Precio formateado (ej: 125.000€)",
              "image": "URL de la imagen proporcionada",
              "url": "URL de la propiedad proporcionada",
              "details": "Breve resumen (ej: 3 hab, 2 baños, Barcelona)"
            }
          ]
        }
        Si no encuentras una propiedad que encaje perfectamente, responde amigablemente en "message" y deja el array "properties" vacío. NUNCA inventes datos.';

        try {
            \Log::info('Mistral: Enviando petición a la API de Mistral...');
            $response = Http::withoutVerifying()
                ->withToken($this->apiKey)
                ->timeout(30)
                ->post($this->apiUrl, [
                    'model' => 'mistral-large-latest',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $systemPrompt . "\n\n" . $context
                        ],
                        [
                            'role' => 'user',
                            'content' => $message
                        ]
                    ],
                    'response_format' => ['type' => 'json_object'],
                    'temperature' => 0.2,
                ]);

            if ($response->successful()) {
                $content = $response->json('choices.0.message.content');

                // Limpieza de caracteres que puedan romper el JSON o la codificación
                $content = mb_convert_encoding($content, 'UTF-8', 'UTF-8');

                // Registro del contenido de la respuesta para depuración
                if (empty($content)) {
                    Log::warning('Mistral devolvió un contenido vacío.');
                    return 'El asistente no pudo generar una respuesta. Por favor, intenta de nuevo.';
                }
                return $content;
            } else {
                Log::error('Mistral Chat Error: ' . $response->body());
                return 'Hubo un error al comunicarse con el asistente. Inténtelo más tarde.';
            }
        } catch (\Exception $e) {
            Log::error('Mistral Chat Exception: ' . $e->getMessage());
            return 'Ocurrió un problema inesperado. Inténtelo más tarde.';
        }
    }
}
