document.addEventListener('DOMContentLoaded', () => {
    const tabs = document.querySelectorAll('.search-tab');
    const operacionInput = document.getElementById('search-operacion');

    if (tabs.length > 0 && operacionInput) {
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // Remove active class from all tabs
                tabs.forEach(t => t.classList.remove('active'));
                
                // Add active class to clicked tab
                tab.classList.add('active');
                
                // Update hidden input value
                const op = tab.getAttribute('data-op');
                operacionInput.value = op;
                
                console.log('Operación cambiada a:', op);
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
            
            if (isDeleting) {
                dynamicWord.textContent = currentWord.substring(0, charIndex - 1);
                charIndex--;
                typeSpeed = 80;
            } else {
                dynamicWord.textContent = currentWord.substring(0, charIndex + 1);
                charIndex++;
                typeSpeed = 120;
            }

            if (!isDeleting && charIndex === currentWord.length) {
                isDeleting = true;
                typeSpeed = 2000; // Pause at end
            } else if (isDeleting && charIndex === 0) {
                isDeleting = false;
                wordIndex = (wordIndex + 1) % words.length;
                typeSpeed = 500; // Pause before new word
            }

            setTimeout(type, typeSpeed);
        }

        type();
    }
});
