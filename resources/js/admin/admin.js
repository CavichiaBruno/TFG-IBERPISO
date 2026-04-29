/**
 * IberPiso — Lógica del Panel de Administración
 * Gestiona el menú lateral móvil y los modales de gestión de usuarios.
 */

document.addEventListener('DOMContentLoaded', function () {
    
    // ─── Toggle del Menú Lateral (Mobile) ──────────────────────────────────────
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('admin-sidebar');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', () => sidebar.classList.toggle('active'));
    }

    // ─── Gestión de Modales de Usuario ────────────────────────────────────────
    const userModal = document.getElementById('user-modal');
    const openCreateBtn = document.getElementById('open-create-user');
    const editBtns = document.querySelectorAll('.edit-user-btn');
    const closeBtns = [
        document.getElementById('close-user-modal'),
        document.getElementById('close-user-modal-2')
    ];
    
    const userForm = document.getElementById('user-form');
    const modalTitle = document.getElementById('modal-title');
    const pwdHint = document.getElementById('pwd-hint');

    /**
     * Abre el modal configurado para crear o editar
     */
    function openModal(isEdit = false, data = {}) {
        if (!userModal) return;
        
        modalTitle.textContent = isEdit ? 'Editar Usuario' : 'Nuevo Usuario';
        pwdHint.textContent = isEdit ? '(dejar vacío para mantener)' : '(obligatoria)';
        
        // Rellenamos los campos del formulario con los datos recibidos
        document.getElementById('user-id').value = data.id || '';
        document.getElementById('u-name').value = data.name || '';
        document.getElementById('u-email').value = data.email || '';
        document.getElementById('u-phone').value = data.phone || '';
        document.getElementById('u-role').value = data.role || 'user';
        document.getElementById('u-password').value = '';
        document.getElementById('u-password').required = !isEdit;

        userModal.classList.add('active');
    }

    function closeModal() {
        if (userModal) userModal.classList.remove('active');
    }

    // Eventos para abrir el modal
    if (openCreateBtn) openCreateBtn.addEventListener('click', () => openModal(false));

    editBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            openModal(true, {
                id: this.dataset.id,
                name: this.dataset.name,
                email: this.dataset.email,
                phone: this.dataset.phone,
                role: this.dataset.role
            });
        });
    });

    // Eventos para cerrar el modal
    closeBtns.forEach(btn => { if (btn) btn.addEventListener('click', closeModal); });
    const overlay = document.querySelector('.modal-overlay');
    if (overlay) overlay.addEventListener('click', closeModal);

    // ─── Envío del Formulario (Simulación o Real) ──────────────────────────
    if (userForm) {
        userForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const userId = document.getElementById('user-id').value;
            
            // Aquí iría la petición fetch real a Laravel
            alert('Datos guardados correctamente (Simulación de TFG). ID: ' + (userId || 'Nuevo'));
            closeModal();
        });
    }
});
