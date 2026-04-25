/**
 * Scroll Tinder Logic
 */

document.addEventListener('DOMContentLoaded', () => {
    const stack = document.querySelector('.swipe-card-stack');
    const cards = Array.from(document.querySelectorAll('.swipe-card'));
    let currentIndex = 0; // Start from the top card (z-index 100)

    if (cards.length === 0) return;

    // Handle button clicks
    document.querySelector('.btn-dislike').addEventListener('click', () => swipe('left'));
    document.querySelector('.btn-like').addEventListener('click', () => swipe('right'));

    // Handle Keyboard
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') swipe('left');
        if (e.key === 'ArrowRight') swipe('right');
    });

    function swipe(direction) {
        if (currentIndex >= cards.length) return;

        const card = cards[currentIndex];
        const propertyId = card.dataset.id;
        const type = direction === 'right' ? 'like' : 'dislike';

        // Clear inline styles to let CSS animation take over
        card.style.transform = '';
        card.style.transition = '';
        card.style.opacity = '';
        card.style.pointerEvents = 'none';

        // Add animation class
        card.classList.add(direction === 'right' ? 'swipe-out-right' : 'swipe-out-left');

        // Send to server
        sendInteraction(propertyId, type);

        currentIndex++;

        // Check if empty
        if (currentIndex >= cards.length) {
            setTimeout(() => {
                const container = document.querySelector('.scroll-container');
                if (container) {
                    container.innerHTML = `
                        <div class="scroll-empty">
                            <svg viewBox="0 0 24 24" width="80" height="80" fill="none" stroke="currentColor" stroke-width="1"><path d="M3 9l9-7 9 7v11H3V9z"/></svg>
                            <h2>¡Eso es todo por ahora en IberScroll!</h2>
                            <p class="text-secondary">No hay más propiedades disponibles para swipear en este momento.</p>
                            <a href="/guardados" class="btn btn-primary" style="margin-top: 1.5rem">Ver mis guardados</a>
                        </div>
                    `;
                }
                const actions = document.querySelector('.swipe-actions');
                if (actions) actions.style.display = 'none';
            }, 500);
        }
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

    // Basic Touch/Drag support
    let startX = 0;
    let currentX = 0;
    let isDragging = false;

    stack.addEventListener('mousedown', startDrag);
    stack.addEventListener('touchstart', (e) => startDrag(e.touches[0]));

    window.addEventListener('mousemove', drag);
    window.addEventListener('touchmove', (e) => drag(e.touches[0]));

    window.addEventListener('mouseup', endDrag);
    window.addEventListener('touchend', endDrag);

    function startDrag(e) {
        if (currentIndex >= cards.length) return;
        isDragging = true;
        startX = e.clientX;
        cards[currentIndex].style.transition = 'none';
    }

    function drag(e) {
        if (!isDragging) return;
        currentX = e.clientX - startX;
        const card = cards[currentIndex];
        const rotate = currentX / 10;
        card.style.transform = `translateX(${currentX}px) rotate(${rotate}deg)`;

        // Badges
        const likeBadge = card.querySelector('.badge-like');
        const dislikeBadge = card.querySelector('.badge-dislike');

        if (currentX > 50) {
            likeBadge.style.opacity = Math.min(currentX / 150, 1);
            dislikeBadge.style.opacity = 0;
        } else if (currentX < -50) {
            dislikeBadge.style.opacity = Math.min(-currentX / 150, 1);
            likeBadge.style.opacity = 0;
        } else {
            likeBadge.style.opacity = 0;
            dislikeBadge.style.opacity = 0;
        }
    }

    function endDrag() {
        if (!isDragging) return;
        isDragging = false;
        const card = cards[currentIndex];
        card.style.transition = 'transform 0.3s ease-out, opacity 0.3s';

        if (currentX > 150) {
            swipe('right');
        } else if (currentX < -150) {
            swipe('left');
        } else {
            card.style.transform = 'translateX(0) rotate(0)';
            card.querySelector('.badge-like').style.opacity = 0;
            card.querySelector('.badge-dislike').style.opacity = 0;
        }
        currentX = 0;
    }
});
