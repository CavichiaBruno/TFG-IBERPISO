/**
 * IberPiso — Admin Panel Logic
 */

document.addEventListener('DOMContentLoaded', function () {
    
    // ─── Sidebar Toggle (Mobile) ──────────────────────────────────────
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('admin-sidebar');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function () {
            sidebar.classList.toggle('active');
        });
    }

    // ─── User Management Modal ────────────────────────────────────────
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

    function openModal(isEdit = false, data = {}) {
        if (!userModal) return;
        
        modalTitle.textContent = isEdit ? 'Editar Usuario' : 'Nuevo Usuario';
        pwdHint.textContent = isEdit ? '(dejar vacío para mantener)' : '(obligatoria)';
        
        // Fill form fields
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

    if (openCreateBtn) {
        openCreateBtn.addEventListener('click', () => openModal(false));
    }

    editBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const data = {
                id: this.dataset.id,
                name: this.dataset.name,
                email: this.dataset.email,
                phone: this.dataset.phone,
                role: this.dataset.role
            };
            openModal(true, data);
        });
    });

    closeBtns.forEach(btn => {
        if (btn) btn.addEventListener('click', closeModal);
    });

    // Close modal on overlay click
    const overlay = document.querySelector('.modal-overlay');
    if (overlay) overlay.addEventListener('click', closeModal);

    // ─── Form Submission (Placeholder for actual implementation) ──────────
    if (userForm) {
        userForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(userForm);
            const userId = document.getElementById('user-id').value;
            const url = userId ? `/admin/users/${userId}` : '/admin/users';
            const method = userId ? 'PUT' : 'POST';

            // Convert FormData to JSON for API if needed, or send as FormData
            // For now, we'll assume a standard Laravel form submission or simple alert
            console.log('Submitting to:', url, 'Method:', method);
            alert('Lógica de guardado enviada (Simulación). ID: ' + (userId || 'Nuevo'));
            closeModal();
            // window.location.reload(); // In real implementation
        });
    }
});
