<div id="chatbot-wrapper" style="position: fixed; bottom: 24px; left: 24px; z-index: 9999; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    
    <!-- Chat Window -->
    <div id="chatbot-window" style="display: none; width: 340px; max-width: calc(100vw - 48px); background: #ffffff; border-radius: 20px; box-shadow: 0 12px 40px rgba(0,0,0,0.12); border: 1px solid rgba(0,0,0,0.1); overflow: hidden; margin-bottom: 16px; flex-direction: column; transition: all 0.3s ease;">
        
        <!-- Header -->
        <div style="background: #1d1d1f; color: #ffffff; padding: 16px 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.1);">
            <div>
                <h4 style="margin: 0; font-size: 15px; font-weight: 600; color: #ffffff !important; line-height: 1.2;">Asistente IberPiso</h4>
                <div style="display: flex; align-items: center; gap: 5px; margin-top: 3px;">
                    <div style="width: 6px; height: 6px; background: #34c759; border-radius: 50%;"></div>
                    <span style="font-size: 11px; color: rgba(255,255,255,0.6); font-weight: 500;">En línea</span>
                </div>
            </div>
            <button id="chatbot-close" style="background: rgba(255,255,255,0.1); border: none; color: white; cursor: pointer; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>

        <!-- Messages Area -->
        <div id="chatbot-messages" style="height: 320px; padding: 16px; overflow-y: auto; background: #fbfbfd; display: flex; flex-direction: column; gap: 12px; scrollbar-width: thin;">
            <!-- Welcome Message -->
            <div style="display: flex; gap: 10px; align-items: flex-start;">
                <div style="width: 30px; height: 30px; border-radius: 10px; background: #1d1d1f; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    <svg width="16" height="16" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div style="background: #ffffff; border: 1px solid rgba(0,0,0,0.08); border-radius: 15px; border-top-left-radius: 4px; padding: 10px 14px; color: #1d1d1f; font-size: 13.5px; line-height: 1.4; box-shadow: 0 2px 5px rgba(0,0,0,0.02); max-width: 85%;">
                    ¡Hola! Soy el asistente de IberPiso. ¿En qué te puedo ayudar hoy?
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div style="padding: 12px 16px; background: #ffffff; border-top: 1px solid rgba(0,0,0,0.05);">
            <form id="chatbot-form" style="display: flex; gap: 10px; margin: 0; background: #f5f5f7; border-radius: 24px; padding: 4px 4px 4px 16px; align-items: center; border: 1px solid rgba(0,0,0,0.05);">
                <input type="text" id="chatbot-input" placeholder="Pregunta por un piso..." required autocomplete="off" style="flex: 1; border: none; background: transparent; padding: 8px 0; font-size: 14px; outline: none; color: #1d1d1f; font-weight: 400;">
                <button type="submit" style="background: #0071e3; color: white; border: none; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: transform 0.2s, background 0.2s;" onmouseover="this.style.background='#0077ed'; this.style.transform='scale(1.05)'" onmouseout="this.style.background='#0071e3'; this.style.transform='scale(1)'">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                </button>
            </form>
        </div>

    </div>

    <!-- Toggle Button -->
    <button id="chatbot-toggle" style="width: 56px; height: 56px; border-radius: 18px; background: #1d1d1f; color: white !important; border: none; box-shadow: 0 8px 24px rgba(0,0,0,0.15); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: white !important;">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
        </svg>
    </button>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('chatbot-toggle');
    const closeBtn = document.getElementById('chatbot-close');
    const chatWindow = document.getElementById('chatbot-window');
    const form = document.getElementById('chatbot-form');
    const input = document.getElementById('chatbot-input');
    const messagesBox = document.getElementById('chatbot-messages');

    let isOpen = false;

    function toggleChat() {
        isOpen = !isOpen;
        if (isOpen) {
            chatWindow.style.display = 'flex';
            toggleBtn.style.transform = 'scale(0) rotate(-90deg)';
            toggleBtn.style.opacity = '0';
            setTimeout(() => input.focus(), 100);
        } else {
            chatWindow.style.display = 'none';
            toggleBtn.style.transform = 'scale(1) rotate(0deg)';
            toggleBtn.style.opacity = '1';
        }
    }

    toggleBtn.addEventListener('click', toggleChat);
    closeBtn.addEventListener('click', toggleChat);

    function escapeHtml(unsafe) {
        if (!unsafe) return '';
        return unsafe.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
    }

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const message = input.value.trim();
        if (!message) return;

        messagesBox.insertAdjacentHTML('beforeend', `
            <div style="display: flex; gap: 8px; justify-content: flex-end; align-items: flex-start;">
                <div style="background: #0071e3; color: white !important; border-radius: 15px; border-top-right-radius: 4px; padding: 10px 14px; font-size: 13.5px; line-height: 1.4; box-shadow: 0 2px 8px rgba(0,113,227,0.15); max-width: 85%;">
                    ${escapeHtml(message)}
                </div>
            </div>
        `);
        
        input.value = '';
        messagesBox.scrollTop = messagesBox.scrollHeight;

        const loadingId = 'loading-' + Date.now();
        messagesBox.insertAdjacentHTML('beforeend', `
            <div id="${loadingId}" style="display: flex; gap: 10px; align-items: flex-start;">
                <div style="width: 30px; height: 30px; border-radius: 10px; background: #1d1d1f; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg width="16" height="16" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div style="background: #ffffff; border: 1px solid rgba(0,0,0,0.08); border-radius: 15px; border-top-left-radius: 4px; padding: 10px 14px; color: #86868b; font-size: 13px; font-style: italic;">
                    Escribiendo...
                </div>
            </div>
        `);
        messagesBox.scrollTop = messagesBox.scrollHeight;

        try {
            const response = await fetch('{{ route("chatbot.chat") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message })
            });

            const data = await response.json();
            document.getElementById(loadingId).remove();

            let botMessage = '';
            let propertiesHtml = '';

            try {
                let cleanResponse = data.response.trim();
                if (cleanResponse.includes('```')) {
                    cleanResponse = cleanResponse.replace(/```json/g, '').replace(/```/g, '').trim();
                }

                const json = JSON.parse(cleanResponse);
                botMessage = (json.message || '').trim();
                
                if (json.properties && json.properties.length > 0) {
                    propertiesHtml = '<div style="display: flex; flex-direction: column; gap: 6px; margin-top: 8px;">';
                    json.properties.forEach(p => {
                        propertiesHtml += `
                            <a href="${p.url}" target="_blank" style="display: grid; grid-template-columns: 40px 1fr 14px; gap: 12px; background: #ffffff; border: 1px solid rgba(0,0,0,0.06); border-radius: 12px; padding: 6px 10px; text-decoration: none; color: #1d1d1f !important; align-items: center; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.02);" onmouseover="this.style.background='#fbfbfd'; this.style.borderColor='rgba(0,113,227,0.3)'" onmouseout="this.style.background='#ffffff'; this.style.borderColor='rgba(0,0,0,0.06)'">
                                <div style="width: 40px; height: 40px; border-radius: 8px; overflow: hidden; background: #f5f5f7; display: flex; align-items: center; justify-content: center; position: relative;">
                                    <img src="${p.image}" style="width: 100%; height: 100%; object-fit: cover; position: relative; z-index: 2;" onerror="this.style.display='none'">
                                    <span style="position: absolute; z-index: 1; font-size: 18px;">🏠</span>
                                </div>
                                <div style="min-width: 0;">
                                    <div style="font-size: 12px; font-weight: 600; color: #1d1d1f !important; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 1px;">${escapeHtml(p.title)}</div>
                                    <div style="font-size: 11.5px; color: #0071e3 !important; font-weight: 700;">${escapeHtml(p.price)}</div>
                                </div>
                                <div style="color: #c1c1c6;">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5l7 7-7 7"></path></svg>
                                </div>
                            </a>
                        `;
                    });
                    propertiesHtml += '</div>';
                }
            } catch (e) {
                botMessage = data.response || 'Sin respuesta.';
            }

            messagesBox.insertAdjacentHTML('beforeend', `
                <div style="display: flex; gap: 10px; align-items: flex-start;">
                    <div style="width: 30px; height: 30px; border-radius: 10px; background: #1d1d1f; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                        <svg width="16" height="16" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div style="background: #ffffff; border: 1px solid rgba(0,0,0,0.08); border-radius: 15px; border-top-left-radius: 4px; padding: 10px 14px; color: #1d1d1f !important; box-shadow: 0 2px 5px rgba(0,0,0,0.02); max-width: 85%;">
                        <div style="font-size: 13.5px; line-height: 1.4; white-space: pre-wrap;">${escapeHtml(botMessage)}</div>
                        ${propertiesHtml}
                    </div>
                </div>
            `);
            messagesBox.scrollTop = messagesBox.scrollHeight;
        } catch (error) {
            document.getElementById(loadingId).remove();
            messagesBox.insertAdjacentHTML('beforeend', `
                <div style="display: flex; gap: 10px; justify-content: center;">
                    <div style="background: #fff2f2; color: #ff3b30; font-size: 12px; padding: 6px 12px; border-radius: 20px; border: 1px solid rgba(255,59,48,0.1);">
                        Error de conexión. Inténtalo de nuevo.
                    </div>
                </div>
            `);
            messagesBox.scrollTop = messagesBox.scrollHeight;
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
});
</script>
