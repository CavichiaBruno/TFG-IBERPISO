import './bootstrap';

// --- GLOBAL LOTTIE OPTIMIZATION ---
document.addEventListener('DOMContentLoaded', () => {
    // Optimization: Only play Lotties when near viewport
    const lottieObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            const lottie = entry.target;
            
            if (entry.isIntersecting) {
                // Play animation
                lottie.setAttribute('playing', 'true');
                if (typeof lottie.play === 'function') {
                    try { lottie.play(); } catch(e) {}
                }
            } else {
                // Pause animation when out of view
                lottie.removeAttribute('playing');
                if (typeof lottie.pause === 'function') {
                    try { lottie.pause(); } catch(e) {}
                }
            }
        });
    }, { 
        threshold: 0.01,
        rootMargin: '200px' 
    });

    const initLottieOptimization = () => {
        document.querySelectorAll('dotlottie-wc').forEach(lottie => {
            lottieObserver.observe(lottie);
        });
    };

    // Initialize
    initLottieOptimization();
    if (window.customElements) {
        customElements.whenDefined('dotlottie-wc').then(initLottieOptimization);
    }
    
    // MutationObserver to catch any Lotties added later (like in popups/overlays)
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
