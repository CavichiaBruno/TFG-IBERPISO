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
});
