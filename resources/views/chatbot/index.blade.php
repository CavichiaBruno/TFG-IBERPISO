@extends('layouts.app')

@section('title', 'Chatbot Asistente')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-sm border border-neutral-200 overflow-hidden">
        <div class="bg-neutral-900 px-6 py-4">
            <h1 class="text-xl font-medium text-white">Asistente Virtual IberPiso</h1>
            <p class="text-sm text-neutral-400">Responde tus dudas de forma rápida y sencilla.</p>
        </div>
        
        <div id="chat-box" class="p-6 h-96 overflow-y-auto bg-neutral-50 flex flex-col gap-4">
            <!-- Initial message -->
            <div class="flex gap-3">
                <div class="w-8 h-8 rounded-full bg-neutral-900 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div class="bg-white border border-neutral-200 rounded-2xl rounded-tl-none px-4 py-2 shadow-sm text-sm text-neutral-800">
                    ¡Hola! Soy el asistente de IberPiso. ¿En qué te puedo ayudar hoy?
                </div>
            </div>
        </div>

        <div class="p-4 bg-white border-t border-neutral-200">
            <form id="chat-form" class="flex gap-2">
                @csrf
                <input type="text" id="chat-input" class="flex-1 rounded-full border-neutral-300 shadow-sm focus:border-neutral-900 focus:ring-neutral-900 px-4 text-sm" placeholder="Escribe tu mensaje..." required autocomplete="off">
                <button type="submit" class="bg-neutral-900 hover:bg-black text-white px-6 py-2 rounded-full text-sm font-medium transition-colors">
                    Enviar
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('chat-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const input = document.getElementById('chat-input');
        const message = input.value.trim();
        if (!message) return;
        
        const chatBox = document.getElementById('chat-box');
        
        // Append user message
        const userMsgHtml = `
            <div class="flex gap-3 justify-end">
                <div class="bg-neutral-900 text-white rounded-2xl rounded-tr-none px-4 py-2 shadow-sm text-sm">
                    ${escapeHtml(message)}
                </div>
            </div>
        `;
        chatBox.insertAdjacentHTML('beforeend', userMsgHtml);
        
        // Clear input and scroll down
        input.value = '';
        chatBox.scrollTop = chatBox.scrollHeight;
        
        // Append loading
        const loadingId = 'loading-' + Date.now();
        const loadingHtml = `
            <div id="${loadingId}" class="flex gap-3">
                <div class="w-8 h-8 rounded-full bg-neutral-900 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div class="bg-white border border-neutral-200 rounded-2xl rounded-tl-none px-4 py-2 shadow-sm text-sm text-neutral-500 animate-pulse">
                    Escribiendo...
                </div>
            </div>
        `;
        chatBox.insertAdjacentHTML('beforeend', loadingHtml);
        chatBox.scrollTop = chatBox.scrollHeight;
        
        try {
            const token = document.querySelector('input[name="_token"]').value;
            const response = await fetch('{{ route("chatbot.chat") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message })
            });
            
            const data = await response.json();
            
            // Remove loading
            document.getElementById(loadingId).remove();
            
            // Append bot message
            const botMsgHtml = `
                <div class="flex gap-3">
                    <div class="w-8 h-8 rounded-full bg-neutral-900 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div class="bg-white border border-neutral-200 rounded-2xl rounded-tl-none px-4 py-2 shadow-sm text-sm text-neutral-800 whitespace-pre-wrap">
                        ${escapeHtml(data.response || 'No se obtuvo respuesta.')}
                    </div>
                </div>
            `;
            chatBox.insertAdjacentHTML('beforeend', botMsgHtml);
            chatBox.scrollTop = chatBox.scrollHeight;
            
        } catch (error) {
            document.getElementById(loadingId).remove();
            const errorHtml = `
                <div class="flex gap-3">
                    <div class="bg-red-100 border border-red-200 rounded-2xl rounded-tl-none px-4 py-2 text-sm text-red-600">
                        Ocurrió un error al enviar el mensaje.
                    </div>
                </div>
            `;
            chatBox.insertAdjacentHTML('beforeend', errorHtml);
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    });

    function escapeHtml(unsafe) {
        return unsafe
             .replace(/&/g, "&amp;")
             .replace(/</g, "&lt;")
             .replace(/>/g, "&gt;")
             .replace(/"/g, "&quot;")
             .replace(/'/g, "&#039;");
    }
</script>
@endsection
