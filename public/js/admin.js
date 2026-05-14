/* ── IberPiso — Admin Panel JS ── */
document.addEventListener('DOMContentLoaded', function () {
    var csrf = document.querySelector('meta[name="csrf-token"]');
    var csrfToken = csrf ? csrf.getAttribute('content') : '';

    // ─── Property: delete button ──────────────────────────────────────
    var deleteModal   = document.getElementById('delete-modal');
    var confirmDelete = document.getElementById('confirm-delete');
    var cancelDelete  = document.getElementById('cancel-delete');
    var deleteModalOverlay = document.getElementById('delete-modal-overlay');
    var pendingDeleteId = null;

    function openDeleteModal(id) {
        pendingDeleteId = id;
        if (deleteModal) { deleteModal.classList.add('open'); document.body.style.overflow = 'hidden'; }
    }
    function closeDeleteModal() {
        pendingDeleteId = null;
        if (deleteModal) { deleteModal.classList.remove('open'); document.body.style.overflow = ''; }
    }

    document.querySelectorAll('.delete-btn').forEach(function (btn) {
        btn.addEventListener('click', function () { openDeleteModal(this.dataset.id); });
    });
    if (cancelDelete) cancelDelete.addEventListener('click', closeDeleteModal);
    if (deleteModalOverlay) deleteModalOverlay.addEventListener('click', closeDeleteModal);

    if (confirmDelete) {
        confirmDelete.addEventListener('click', function () {
            if (!pendingDeleteId) return;
            var btn = this;
            btn.disabled = true;
            btn.textContent = 'Eliminando…';

            fetch('/admin/propiedades/' + pendingDeleteId, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (res.success) {
                    var row = document.querySelector('tr[data-id="' + pendingDeleteId + '"]');
                    if (row) row.remove();
                    closeDeleteModal();
                }
            })
            .finally(function () { btn.disabled = false; btn.textContent = 'Eliminar'; });
        });
    }

    // ─── Property: toggle active ──────────────────────────────────────
    document.querySelectorAll('.toggle-active-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id = this.dataset.id;
            var self = this;
            fetch('/admin/propiedades/' + id + '/toggle-active', {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (res.success) {
                    self.classList.toggle('active', res.activa);
                    self.classList.toggle('inactive', !res.activa);
                    self.title = res.activa ? 'Desactivar' : 'Activar';
                }
            });
        });
    });

    // ─── Admin search (debounced) ─────────────────────────────────────
    var adminSearch = document.getElementById('admin-search');
    if (adminSearch) {
        var debounceTimer;
        adminSearch.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            var q = this.value;
            debounceTimer = setTimeout(function () {
                var url = new URL(window.location.href);
                url.searchParams.set('q', q);
                window.location.href = url.toString();
            }, 600);
        });
    }

    // ─── Inquiry status update ────────────────────────────────────────
    document.querySelectorAll('.status-select').forEach(function (sel) {
        sel.addEventListener('change', function () {
            var id = this.dataset.id;
            var status = this.value;
            fetch('/admin/consultas/' + id + '/estado', {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ status: status }),
            })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (res.success) {
                    var row = sel.closest('tr');
                    if (row && status !== 'pending') { row.classList.remove('row-unread'); }
                }
            });
        });
    });

    // ─── Delete inquiry ───────────────────────────────────────────────
    document.querySelectorAll('.delete-inquiry-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (!confirm('¿Eliminar esta consulta?')) return;
            var id = this.dataset.id;
            var row = this.closest('tr');
            fetch('/admin/consultas/' + id, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            })
            .then(function (r) { return r.json(); })
            .then(function (res) { if (res.success && row) row.remove(); });
        });
    });

    // ─── User CRUD (modal) ────────────────────────────────────────────
    var userModal     = document.getElementById('user-modal');
    var userForm      = document.getElementById('user-form');
    var openCreateBtn = document.getElementById('open-create-user');
    var closeBtn1     = document.getElementById('close-user-modal');
    var closeBtn2     = document.getElementById('close-user-modal-2');

    function openUserModal(title, data) {
        if (!userModal) return;
        document.getElementById('modal-title').textContent = title;
        document.getElementById('user-id').value   = data.id || '';
        document.getElementById('u-name').value    = data.nombre || '';
        document.getElementById('u-email').value   = data.correo || '';
        document.getElementById('u-phone').value   = data.telefono || '';
        document.getElementById('u-role').value    = data.rol || 'usuario';
        document.getElementById('u-password').value = '';
        document.getElementById('u-password').required = !data.id;
        var pwdHint = document.getElementById('pwd-hint');
        if (pwdHint) pwdHint.textContent = data.id ? '(dejar en blanco para no cambiar)' : '(obligatoria)';
        var errorDiv = document.getElementById('user-form-error');
        if (errorDiv) { errorDiv.style.display = 'none'; errorDiv.textContent = ''; }
        userModal.classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeUserModal() {
        if (userModal) { userModal.classList.remove('open'); document.body.style.overflow = ''; }
    }

    if (openCreateBtn) openCreateBtn.addEventListener('click', function () { openUserModal('Nuevo usuario', {}); });
    if (closeBtn1) closeBtn1.addEventListener('click', closeUserModal);
    if (closeBtn2) closeBtn2.addEventListener('click', closeUserModal);

    document.querySelectorAll('.edit-user-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            openUserModal('Editar usuario', {
                id: this.dataset.id, nombre: this.dataset.name,
                correo: this.dataset.email, telefono: this.dataset.phone, rol: this.dataset.role,
            });
        });
    });

    if (userForm) {
        userForm.addEventListener('submit', function (e) {
            e.preventDefault();
            var userId = document.getElementById('user-id').value;
            var url    = userId ? '/admin/usuarios/' + userId : '/admin/usuarios';
            var method = userId ? 'PUT' : 'POST';

            var payload = {
                nombre:     document.getElementById('u-name').value,
                correo:    document.getElementById('u-email').value,
                telefono:    document.getElementById('u-phone').value,
                rol:     document.getElementById('u-role').value,
                contrasena: document.getElementById('u-password').value,
            };
            if (userId) payload['_method'] = 'PUT';

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify(payload),
            })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (res.success) { closeUserModal(); window.location.reload(); }
                else if (res.error) {
                    var errorDiv = document.getElementById('user-form-error');
                    if (errorDiv) { errorDiv.style.display = 'block'; errorDiv.textContent = res.error; }
                }
            });
        });
    }

    // ─── Toggle user active ───────────────────────────────────────────
    document.querySelectorAll('.toggle-user-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id = this.dataset.id;
            var self = this;
            fetch('/admin/usuarios/' + id + '/toggle-active', {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (res.success) {
                    self.classList.toggle('active', res.activo);
                    self.classList.toggle('inactive', !res.activo);
                    self.textContent = res.activo ? 'Activo' : 'Inactivo';
                }
            });
        });
    });

    // ─── Delete user ──────────────────────────────────────────────────
    document.querySelectorAll('.delete-user-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (!confirm('¿Eliminar este usuario?')) return;
            var id = this.dataset.id;
            var row = this.closest('tr');
            fetch('/admin/usuarios/' + id, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (res.success && row) row.remove();
                else if (res.error) alert(res.error);
            });
        });
    });
});
