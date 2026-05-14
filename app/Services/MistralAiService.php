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
        $this->apiKey = config('services.mistral.key', '');
    }

    /**
     * Analiza la imagen de una propiedad para generar un título sugerido, una descripción y recomendaciones.
     *
     * @param string $base64Image La representación en base64 de la imagen.
     * @return array|null Un array que contiene 'title', 'description' y 'recommendations', o null si falla.
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
     * Envía un mensaje de chat básico a la API de Mistral.
     *
     * @param string $message El mensaje del usuario.
     * @return string La respuesta del asistente.
     */
    public function chat(string $message): string
    {
        if (empty($this->apiKey)) {
            return 'La API Key de Mistral no está configurada.';
        }

        // Obtiene una lista simple de propiedades disponibles para usar como contexto
        $properties = \Illuminate\Support\Facades\DB::table('propiedades')
            ->leftJoin('medios_propiedades', function($join) {
                $join->on('propiedades.id', '=', 'medios_propiedades.propiedad_id')
                    ->where('medios_propiedades.tipo_archivo', '=', 'imagen')
                    ->where('medios_propiedades.es_portada', '=', \Illuminate\Support\Facades\DB::raw('true'));
            })
            ->whereNull('propiedades.deleted_at')
            ->where('propiedades.activa', \Illuminate\Support\Facades\DB::raw('true'))
            ->select(
                'propiedades.id', 
                'propiedades.slug', 
                'propiedades.titulo', 
                'propiedades.precio', 
                'propiedades.ciudad', 
                'propiedades.habitaciones', 
                'propiedades.banos', 
                'propiedades.tipo_operacion', 
                'propiedades.tipo_propiedad',
                'medios_propiedades.ruta_archivo as imagen'
            )
            ->limit(50)
            ->get();

        $context = "Lista de propiedades disponibles:\n";
        foreach ($properties as $prop) {
            $slug = $prop->slug ?: 'detalle';
            $url = url("/propiedades/{$prop->id}-{$slug}");
            
            $imgUrl = 'https://images.unsplash.com/photo-1560185127-6ed189bf02f4?auto=format&fit=crop&q=80&w=100';
            if ($prop->imagen) {
                if (str_starts_with($prop->imagen, 'http')) {
                    $imgUrl = $prop->imagen;
                } else {
                    // Usamos asset() para generar la URL correcta según el host actual
                    $imgUrl = asset(ltrim($prop->imagen, '/'));
                }
            }
            
            $context .= "ID: {$prop->id} | {$prop->titulo} | {$prop->precio}€ | Enlace: $url | Imagen: $imgUrl\n";
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
            $response = Http::withoutVerifying()
                ->withToken($this->apiKey)
                ->timeout(30)
                ->post($this->apiUrl, [
                    'model' => 'mistral-small-latest',
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
