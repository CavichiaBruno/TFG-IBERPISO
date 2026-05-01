<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MistralAiService;

class AiController extends Controller
{
    protected MistralAiService $aiService;

    public function __construct(MistralAiService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function analyzeImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:10240', // max 10MB
        ]);

        $imageFile = $request->file('image');
        
        // Convert to base64
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
