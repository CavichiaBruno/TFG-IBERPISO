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

});
