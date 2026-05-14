/**
 * IberPiso — Lógica del Panel de Administración
 * Solo gestiona el menú lateral móvil. Todo lo demás es Laravel puro.
 */

document.addEventListener('DOMContentLoaded', function () {
    // ─── Toggle del Menú Lateral (Mobile) ──────────────────────────────────────
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('admin-sidebar');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', () => sidebar.classList.toggle('active'));
    }
});
