/**
 * IberScroll — Lógica para el descubrimiento de viviendas tipo "swipe" (Tinder)
 * Gestiona el arrastre de cartas, animaciones y guardado de datos.
 */

document.addEventListener('DOMContentLoaded', () => {
    const stack = document.querySelector('.swipe-card-stack');
    if (!stack) return;

    // Array con las cartas visibles en el DOM
    let cards = Array.from(stack.querySelectorAll('.swipe-card'));
    
    // Botones de acción inferior
    const btnDislike = document.querySelector('.btn-dislike');
    const btnLike = document.querySelector('.btn-like');

    if (btnDislike) btnDislike.addEventListener('click', () => swipe('left'));
    if (btnLike) btnLike.addEventListener('click', () => swipe('right'));

    // Soporte para teclado (Flechas izq/der)
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') swipe('left');
        if (e.key === 'ArrowRight') swipe('right');
    });

    /**
     * Realiza la acción de deslizar hacia una dirección
     */
    function swipe(direction) {
        if (cards.length === 0) return;

        const card = cards[0]; // La carta superior
        const propertyId = card.dataset.id;
        const type = direction === 'right' ? 'like' : 'dislike';

        // Limpieza de estilos manuales para que la animación CSS actúe
        card.style.transform = '';
        card.style.transition = '';
        card.style.opacity = '';
        card.style.pointerEvents = 'none';

        // Clase de animación de salida
        card.classList.add(direction === 'right' ? 'swipe-out-right' : 'swipe-out-left');

        // Registro asíncrono en la base de datos
        sendInteraction(propertyId, type);

        // Eliminamos la carta del control actual
        cards.shift();

        // Borramos el elemento del DOM tras terminar la animación
        setTimeout(() => {
            card.remove();
            if (cards.length === 0) showEmptyState();
        }, 500);
    }

    /**
     * Muestra el mensaje final cuando no quedan más casas
     */
    function showEmptyState() {
        const stage = document.querySelector('.iberscroll-stage');
        if (stage) {
            stage.innerHTML = `
                <div class="scroll-empty fade-in">
                    <div class="empty-icon">
                        <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M3 9l9-7 9 7v11H3V9z"/></svg>
                    </div>
                    <h2>¡Todo visto por ahora!</h2>
                    <p>Has visto todas las propiedades disponibles.</p>
                    <div style="display:flex; gap:12px; justify-content:center; margin-top:24px;">
                        <a href="/guardados" class="btn btn-primary">Ver mis guardados</a>
                        <a href="/propiedades" class="btn btn-outline">Volver a buscar</a>
                    </div>
                </div>
            `;
        }
    }

    /**
     * Envía la interacción (me gusta/no me gusta) al servidor vía AJAX
     */
    async function sendInteraction(propertyId, type) {
        try {
            await fetch('/scroll/interact', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ property_id: propertyId, type: type })
            });
        } catch (error) {
            console.error('Error al guardar interacción:', error);
        }
    }

    // ─── Lógica de Arrastre (Touch & Drag) ───────────────────────────────────────────
    let startX = 0;
    let currentX = 0;
    let isDragging = false;

    // Listeners para ratón y táctil
    stack.addEventListener('mousedown', startDrag);
    stack.addEventListener('touchstart', (e) => startDrag(e.touches[0]), { passive: true });
    window.addEventListener('mousemove', drag);
    window.addEventListener('touchmove', (e) => drag(e.touches[0]), { passive: false });
    window.addEventListener('mouseup', endDrag);
    window.addEventListener('touchend', endDrag);

    function startDrag(e) {
        if (cards.length === 0) return;
        isDragging = true;
        startX = e.clientX;
        cards[0].style.transition = 'none'; // Quitamos transición mientras arrastramos
    }

    function drag(e) {
        if (!isDragging || cards.length === 0) return;
        
        currentX = e.clientX - startX;
        const card = cards[0];
        const rotate = currentX / 12;
        card.style.transform = `translateX(${currentX}px) rotate(${rotate}deg)`;

        // Feedback visual (sellos de LIKE/NOPE)
        const likeBadge = card.querySelector('.badge-like');
        const nopeBadge = card.querySelector('.badge-nope');

        if (currentX > 40) {
            if (likeBadge) likeBadge.style.opacity = Math.min(currentX / 120, 1);
            if (nopeBadge) nopeBadge.style.opacity = 0;
        } else if (currentX < -40) {
            if (nopeBadge) nopeBadge.style.opacity = Math.min(-currentX / 120, 1);
            if (likeBadge) likeBadge.style.opacity = 0;
        } else {
            if (likeBadge) likeBadge.style.opacity = 0;
            if (nopeBadge) nopeBadge.style.opacity = 0;
        }
    }

    function endDrag() {
        if (!isDragging || cards.length === 0) return;
        isDragging = false;
        const card = cards[0];
        card.style.transition = 'transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.3s';

        // Si hemos arrastrado suficiente distancia, ejecutamos el swipe
        if (currentX > 120) swipe('right');
        else if (currentX < -120) swipe('left');
        else {
            // Si no, la carta vuelve al centro (efecto muelle)
            card.style.transform = 'translateX(0) rotate(0)';
            if (card.querySelector('.badge-like')) card.querySelector('.badge-like').style.opacity = 0;
            if (card.querySelector('.badge-nope')) card.querySelector('.badge-nope').style.opacity = 0;
        }
        currentX = 0;
    }
});
