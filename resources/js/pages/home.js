document.addEventListener('DOMContentLoaded', () => {
    const tabs = document.querySelectorAll('.search-tab');
    const operacionInput = document.getElementById('search-operacion');

    if (tabs.length > 0 && operacionInput) {
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                const op = tab.getAttribute('data-op');
                operacionInput.value = op;
            });
        });
    }

    // Dynamic Typing Animation
    const dynamicWord = document.getElementById('dynamic-word');
    if (dynamicWord) {
        const words = ['ideal', 'perfecto', 'soñado', 'único', 'especial', 'exclusivo'];
        let wordIndex = 0;
        let charIndex = 0;
        let isDeleting = false;
        let typeSpeed = 150;

        function type() {
            const currentWord = words[wordIndex];
            let typed = currentWord.substring(0, isDeleting ? charIndex - 1 : charIndex + 1);
            
            dynamicWord.textContent = typed;

            if (isDeleting) {
                charIndex--;
                typeSpeed = 80;
            } else {
                charIndex++;
                typeSpeed = 120;
            }

            if (!isDeleting && charIndex === currentWord.length) {
                isDeleting = true;
                typeSpeed = 2000;
            } else if (isDeleting && charIndex === 0) {
                isDeleting = false;
                wordIndex = (wordIndex + 1) % words.length;
                typeSpeed = 500;
            }

            setTimeout(type, typeSpeed);
        }

        type();
    }

    // --- CARROUSEL DE DESTACADOS ---
    const carousel = document.getElementById('featured-carousel');
    const prevBtn = document.getElementById('featured-prev');
    const nextBtn = document.getElementById('featured-next');

    if (carousel && prevBtn && nextBtn) {
        // Función para calcular cuánto desplazar (un tercio del ancho o una tarjeta)
        const getScrollAmount = () => {
            const firstCard = carousel.querySelector('.property-card');
            return firstCard ? firstCard.offsetWidth + 32 : 400; // 32 is the gap (var--sp-8)
        };

        nextBtn.addEventListener('click', () => {
            carousel.scrollBy({ left: getScrollAmount(), behavior: 'smooth' });
        });

        prevBtn.addEventListener('click', () => {
            carousel.scrollBy({ left: -getScrollAmount(), behavior: 'smooth' });
        });

        // Ocultar/Mostrar flechas según el scroll
        carousel.addEventListener('scroll', () => {
            const isAtStart = carousel.scrollLeft <= 10;
            const isAtEnd = carousel.scrollLeft + carousel.offsetWidth >= carousel.scrollWidth - 10;
            
            prevBtn.style.opacity = isAtStart ? '0.3' : '1';
            prevBtn.style.pointerEvents = isAtStart ? 'none' : 'auto';
            
            nextBtn.style.opacity = isAtEnd ? '0.3' : '1';
            nextBtn.style.pointerEvents = isAtEnd ? 'none' : 'auto';
        });

        // Trigger inicial
        carousel.dispatchEvent(new Event('scroll'));

        // Carga asíncrona de datos (Estrategia Skeleton-first)
        if (carousel.dataset.autoload === 'true') {
            fetch(window.location.href, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                carousel.innerHTML = data.html;
                carousel.dataset.autoload = 'false';
                // El MutationObserver o dispatching scroll servirá para actualizar flechas
                carousel.dispatchEvent(new Event('scroll'));
            })
            .catch(err => console.error("Error loading featured:", err));
        }
    }
});
