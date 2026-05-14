/**
 * IberPiso — Lógica del Panel de Administración
 * Gestiona el menú lateral y la interacción UI básica.
 */

document.addEventListener('DOMContentLoaded', function () {
    console.log('IberPiso Admin UI initialized');
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('admin-sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const layout = document.querySelector('.admin-layout');
    
    if (sidebarToggle && sidebar && layout) {
        sidebarToggle.addEventListener('click', (e) => {
            e.preventDefault();
            const isMobile = window.innerWidth <= 1024;
            
            if (isMobile) {
                // Modo Móvil: Toggle Sidebar + Overlay
                sidebar.classList.toggle('active');
                if (overlay) overlay.classList.toggle('active');
            } else {
                // Modo Desktop: Toggle Colapso
                layout.classList.toggle('sidebar-collapsed');
            }
        });

        // Cerrar al hacer click en el overlay (solo móvil)
        if (overlay) {
            overlay.addEventListener('click', () => {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            });
        }
    }
});
