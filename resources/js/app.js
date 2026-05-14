import './bootstrap';

// --- OPTIMIZACIÓN GLOBAL DE LOTTIE ---
document.addEventListener('DOMContentLoaded', () => {
    // Optimización: Solo reproducir Lotties cuando estén cerca del viewport
    const lottieObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            const lottie = entry.target;
            
            if (entry.isIntersecting) {
                lottie.setAttribute('playing', 'true');
                // Asegurarse de intentar reproducirlo. Si no está listo, se reproducirá cuando lo esté.
                if (typeof lottie.play === 'function') {
                    try {
                        lottie.play();
                    } catch (e) {
                        console.warn('Error al reproducir Lottie:', e);
                    }
                } else {
                    // Alternativa para cuando el componente no se ha cargado completamente todavía
                    lottie.addEventListener('ready', () => {
                        try {
                            if (typeof lottie.play === 'function') {
                                lottie.play();
                            }
                        } catch (e) {
                            console.warn('Error al reproducir Lottie al estar listo:', e);
                        }
                    }, { once: true });
                }
            } else {
                lottie.removeAttribute('playing');
                if (typeof lottie.pause === 'function') {
                    try { 
                        lottie.pause(); 
                    } catch(e) {}
                }
            }
        });
    }, { 
        threshold: 0.05,
        rootMargin: '100px' 
    });

    const initLottieOptimization = () => {
        document.querySelectorAll('dotlottie-wc').forEach(lottie => {
            lottieObserver.observe(lottie);
        });
    };

    // Lógica de reintento para cuando los elementos personalizados pueden no estar listos
    let retryCount = 0;
    const maxRetries = 50;
    
    const tryInit = () => {
        if (window.customElements && typeof window.customElements.whenDefined === 'function') {
            customElements.whenDefined('dotlottie-wc').then(() => {
                initLottieOptimization();
            }).catch(err => {
                console.warn('Error esperando a dotlottie-wc:', err);
                initLottieOptimization(); // Intentar de todos modos
            });
        } else if (document.querySelectorAll('dotlottie-wc').length > 0) {
            initLottieOptimization();
        } else if (retryCount < maxRetries) {
            retryCount++;
            setTimeout(tryInit, 100);
        }
    };
    
    // Inicializar
    tryInit();
    
    // MutationObserver para capturar Lotties añadidos dinámicamente (ej. en popups o capas superpuestas)
    const bodyObserver = new MutationObserver((mutations) => {
        mutations.forEach(mutation => {
            mutation.addedNodes.forEach(node => {
                if (node.nodeName === 'DOTLOTTIE-WC') {
                    lottieObserver.observe(node);
                } else if (node.querySelectorAll) {
                    node.querySelectorAll('dotlottie-wc').forEach(l => lottieObserver.observe(l));
                }
            });
        });
    });
    bodyObserver.observe(document.body, { childList: true, subtree: true });
});

// --- LÓGICA DE CARROUSEL EN TARJETAS (GLOBAL) ---
window.changeCardImage = function(event, btn, direction) {
    event.preventDefault();
    event.stopPropagation();
    
    const container = btn.closest('.card-image-container');
    const images = container.querySelectorAll('.card-image');
    const dots = container.querySelectorAll('.dot');
    
    let currentIndex = 0;
    images.forEach((img, i) => {
        if (img.classList.contains('active')) {
            currentIndex = i;
        }
    });
    
    let nextIndex = currentIndex + direction;
    if (nextIndex >= images.length) nextIndex = 0;
    if (nextIndex < 0) nextIndex = images.length - 1;
    
    // Actualizar imágenes
    images[currentIndex].classList.remove('active');
    images[currentIndex].style.display = 'none';
    
    images[nextIndex].classList.add('active');
    images[nextIndex].style.display = 'block';
    
    // Actualizar puntos (indicadores)
    if (dots.length > 0) {
        dots[currentIndex].classList.remove('active');
        dots[nextIndex].classList.add('active');
    }
};
// ── ACCESIBILIDAD Y UTILIDADES ──
window.accTools = {
    changeSize: function(delta) {
        const root = document.documentElement;
        let currentMod = parseFloat(getComputedStyle(root).getPropertyValue('--f-mod')) || 1;
        let newMod = currentMod + (delta * 0.1);
        if (newMod >= 0.8 && newMod <= 1.5) {
            root.style.setProperty('--f-mod', newMod);
            localStorage.setItem('f-mod', newMod);
            if (Math.abs(newMod - 1) > 0.01) {
                document.body.classList.add('is-scaling');
            } else {
                document.body.classList.remove('is-scaling');
            }
        }
    },
    resetSize: function() {
        document.documentElement.style.setProperty('--f-mod', 1);
        document.body.classList.remove('is-scaling');
        localStorage.removeItem('f-mod');
    },
    toggleContrast: function() {
        const isHC = document.body.classList.toggle('high-contrast');
        localStorage.setItem('high-contrast', isHC);
    }
};

// Inicialización de ajustes guardados
(function initAccessibility() {
    const savedMod = localStorage.getItem('f-mod');
    if (savedMod && Math.abs(parseFloat(savedMod) - 1) > 0.01) {
        document.documentElement.style.setProperty('--f-mod', savedMod);
        document.body.classList.add('is-scaling');
    }

    const savedHC = localStorage.getItem('high-contrast');
    if (savedHC === 'true') document.body.classList.add('high-contrast');
})();

// Botón de Volver Arriba
const backToTop = document.getElementById('back-to-top');
if (backToTop) {
    window.addEventListener('scroll', () => {
        if (window.scrollY > 400) {
            backToTop.classList.add('show');
        } else {
            backToTop.classList.remove('show');
        }
    });

    backToTop.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}
