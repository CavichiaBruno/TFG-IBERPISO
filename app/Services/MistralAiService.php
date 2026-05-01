<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MistralAiService
{
    protected string $apiKey;
    protected string $apiUrl = 'https://api.mistral.ai/v1/chat/completions';

    public function __construct()
    {
        $this->apiKey = env('MISTRAL_API_KEY', '');
    }

    /**
     * Analyze a property image to generate a suggested title, description, and recommendations.
     *
     * @param string $base64Image The base64 representation of the image.
     * @return array|null An array containing 'title', 'description', and 'recommendations' or null on failure.
     */
    public function analyzePropertyImage(string $base64Image): ?array
    {
        if (empty($this->apiKey)) {
            Log::error('Mistral API Key is not set.');
            return [
                'title' => 'API Key faltante',
                'description' => 'La API Key de Mistral no está configurada.',
                'recommendations' => 'Por favor, añade MISTRAL_API_KEY en tu archivo .env.'
            ];
        }

        try {
            $response = Http::withToken($this->apiKey)
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
}
