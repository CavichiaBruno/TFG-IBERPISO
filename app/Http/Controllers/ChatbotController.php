<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MistralAiService;

class ChatbotController extends Controller
{
    protected MistralAiService $mistralService;

    public function __construct(MistralAiService $mistralService)
    {
        $this->mistralService = $mistralService;
    }

    public function index()
    {
        return view('chatbot.index');
    }

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
