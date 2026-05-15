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
            console.log('Chatbot response data:', data);

            // Remove loading indicator safely
            document.getElementById(loadingId)?.remove();

            // Añadimos el mensaje de texto del bot
            const botMsgHtml = `
                <div class="flex gap-3 mb-2">
                    <div class="w-8 h-8 rounded-full bg-neutral-900 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div class="bg-white border border-neutral-200 rounded-2xl rounded-tl-none px-4 py-2 shadow-sm text-sm text-neutral-800 whitespace-pre-wrap">
                        ${escapeHtml(data.response || 'No se obtuvo respuesta.')}
                    </div>
                </div>
            `;
            chatBox.insertAdjacentHTML('beforeend', botMsgHtml);

            // Si hay propiedades, las renderizamos con createElement (100% seguro)
            const props = Array.isArray(data.properties) ? data.properties : [];
            console.log('Propiedades recibidas del servidor:', props.length, props);

            if (props.length > 0) {
                const grid = document.createElement('div');
                grid.style.cssText = 'display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:10px;margin-left:44px;margin-bottom:20px;';

                props.forEach(function(prop) {
                    // Card wrapper (enlace)
                    const card = document.createElement('a');
                    card.href   = prop.url || '#';
                    card.target = '_blank';
                    card.rel    = 'noopener noreferrer';
                    card.style.cssText = 'display:block;background:#fff;border:1px solid #e5e5e5;border-radius:12px;overflow:hidden;text-decoration:none;color:inherit;box-shadow:0 1px 3px rgba(0,0,0,0.1);';

                    // Imagen
                    const imgWrap = document.createElement('div');
                    imgWrap.style.cssText = 'aspect-ratio:16/9;background:#f3f3f3;overflow:hidden;';
                    const img = document.createElement('img');
                    img.src   = prop.image || '';
                    img.alt   = prop.title || '';
                    img.style.cssText = 'width:100%;height:100%;object-fit:cover;';
                    img.onerror = function() {
                        this.onerror = null;
                        this.src = 'https://images.unsplash.com/photo-1560185127-6ed189bf02f4?auto=format&fit=crop&q=80&w=400';
                    };
                    imgWrap.appendChild(img);
                    card.appendChild(imgWrap);

                    // Info
                    const info = document.createElement('div');
                    info.style.cssText = 'padding:8px;';

                    const h4 = document.createElement('h4');
                    h4.textContent = prop.title || 'Sin título';
                    h4.style.cssText = 'margin:0;font-size:11px;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;';

                    const p = document.createElement('p');
                    p.textContent = prop.details || '';
                    p.style.cssText = 'margin:2px 0;font-size:10px;color:#666;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;';

                    const price = document.createElement('div');
                    price.textContent = prop.price || '';
                    price.style.cssText = 'margin-top:4px;font-size:12px;font-weight:900;color:#000;';

                    info.appendChild(h4);
                    info.appendChild(p);
                    info.appendChild(price);
                    card.appendChild(info);
                    grid.appendChild(card);
                });

                chatBox.appendChild(grid);
            }

            chatBox.scrollTop = chatBox.scrollHeight;

        } catch (error) {
            console.error('Chatbot fetch error:', error);
            document.getElementById(loadingId)?.remove();
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
        if (unsafe === null || unsafe === undefined) return '';
        return String(unsafe)
             .replace(/&/g, "&amp;")
             .replace(/</g, "&lt;")
             .replace(/>/g, "&gt;")
             .replace(/"/g, "&quot;")
             .replace(/'/g, "&#039;");
    }
</script>
@endsection
