/**
 * IberScroll — Tinder-style discovery logic
 * Optimized with card stack animations and smooth transitions
 */

document.addEventListener('DOMContentLoaded', () => {
    const stack = document.querySelector('.swipe-card-stack');
    if (!stack) return;

    let cards = Array.from(stack.querySelectorAll('.swipe-card'));
    
    // Initial setup for the stack is handled by CSS nth-child rules.
    // We just need to handle the interactions.

    // Handle button clicks
    const btnDislike = document.querySelector('.btn-dislike');
    const btnLike = document.querySelector('.btn-like');

    if (btnDislike) btnDislike.addEventListener('click', () => swipe('left'));
    if (btnLike) btnLike.addEventListener('click', () => swipe('right'));

    // Handle Keyboard
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') swipe('left');
        if (e.key === 'ArrowRight') swipe('right');
    });

    function swipe(direction) {
        if (cards.length === 0) return;

        const card = cards[0]; // Always get the top card
        const propertyId = card.dataset.id;
        const type = direction === 'right' ? 'like' : 'dislike';

        // Clear inline styles to let CSS animation take over
        card.style.transform = '';
        card.style.transition = '';
        card.style.opacity = '';
        card.style.pointerEvents = 'none';

        // Add animation class
        card.classList.add(direction === 'right' ? 'swipe-out-right' : 'swipe-out-left');

        // Send interaction to server
        sendInteraction(propertyId, type);

        // Remove from our tracking array
        cards.shift();

        // Remove from DOM after animation completes
        setTimeout(() => {
            card.remove();
            
            // Check if stack is empty
            if (cards.length === 0) {
                showEmptyState();
            }
        }, 500);
    }

    function showEmptyState() {
        const container = document.querySelector('.scroll-container');
        if (container) {
            container.innerHTML = `
                <div class="scroll-empty fade-in">
                    <svg viewBox="0 0 24 24" width="80" height="80" fill="none" stroke="currentColor" stroke-width="1"><path d="M3 9l9-7 9 7v11H3V9z"/></svg>
                    <h2>¡Eso es todo por ahora!</h2>
                    <p class="text-secondary">Has visto todas las propiedades disponibles.</p>
                    <div style="display:flex; gap:12px; justify-content:center; margin-top:20px;">
                        <a href="/guardados" class="btn btn-primary">Ver mis guardados</a>
                        <a href="/propiedades" class="btn btn-outline">Volver a buscar</a>
                    </div>
                </div>
            `;
        }
        const actions = document.querySelector('.swipe-actions');
        if (actions) actions.style.display = 'none';
        
        const instructions = document.querySelector('.scroll-instructions');
        if (instructions) instructions.style.display = 'none';
    }

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
            console.error('Error sending interaction:', error);
        }
    }

    // ─── Touch & Drag Logic ───────────────────────────────────────────
    let startX = 0;
    let currentX = 0;
    let isDragging = false;

    // We only attach listeners once, but they will always act on cards[0]
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
        cards[0].style.transition = 'none';
    }

    function drag(e) {
        if (!isDragging || cards.length === 0) return;
        
        currentX = e.clientX - startX;
        const card = cards[0];
        const rotate = currentX / 12;
        card.style.transform = `translateX(${currentX}px) rotate(${rotate}deg)`;

        // Visual feedback (Badges)
        const likeBadge = card.querySelector('.badge-like');
        const dislikeBadge = card.querySelector('.badge-dislike');

        if (currentX > 40) {
            likeBadge.style.opacity = Math.min(currentX / 120, 1);
            dislikeBadge.style.opacity = 0;
        } else if (currentX < -40) {
            dislikeBadge.style.opacity = Math.min(-currentX / 120, 1);
            likeBadge.style.opacity = 0;
        } else {
            likeBadge.style.opacity = 0;
            dislikeBadge.style.opacity = 0;
        }
    }

    function endDrag() {
        if (!isDragging || cards.length === 0) return;
        
        isDragging = false;
        const card = cards[0];
        card.style.transition = 'transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.3s';

        if (currentX > 120) {
            swipe('right');
        } else if (currentX < -120) {
            swipe('left');
        } else {
            // Snap back
            card.style.transform = 'translateX(0) rotate(0)';
            if (card.querySelector('.badge-like')) card.querySelector('.badge-like').style.opacity = 0;
            if (card.querySelector('.badge-dislike')) card.querySelector('.badge-dislike').style.opacity = 0;
        }
        currentX = 0;
    }
});
