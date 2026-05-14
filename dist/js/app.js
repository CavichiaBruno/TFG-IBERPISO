/* ── IberPiso — Main JS ── */

document.addEventListener('DOMContentLoaded', function () {

    // ─── Sticky header shadow ─────────────────────────────────────────
    const header = document.getElementById('site-header');
    if (header) {
        window.addEventListener('scroll', function () {
            header.classList.toggle('scrolled', window.scrollY > 20);
        }, { passive: true });
    }

    // ─── Mobile hamburger menu ────────────────────────────────────────
    const hamburger = document.getElementById('hamburger');
    const drawer    = document.getElementById('mobile-drawer');
    const overlay   = document.getElementById('drawer-overlay');

    if (hamburger && drawer) {
        function toggleDrawer() {
            const open = drawer.classList.toggle('open');
            hamburger.setAttribute('aria-expanded', open);
            drawer.setAttribute('aria-hidden', !open);
            if (overlay) overlay.classList.toggle('open', open);
            document.body.style.overflow = open ? 'hidden' : '';
        }
        hamburger.addEventListener('click', toggleDrawer);
        if (overlay) overlay.addEventListener('click', toggleDrawer);
    }

    // ─── Admin sidebar toggle ─────────────────────────────────────────
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const adminSidebar  = document.getElementById('admin-sidebar');

    if (sidebarToggle && adminSidebar) {
        sidebarToggle.addEventListener('click', function () {
            adminSidebar.classList.toggle('open');
        });
    }

    // ─── Password show/hide ───────────────────────────────────────────
    document.querySelectorAll('.toggle-password').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const input = this.closest('.password-wrap').querySelector('input');
            input.type = input.type === 'password' ? 'text' : 'password';
        });
    });

    // ─── Alert auto-close ─────────────────────────────────────────────
    document.querySelectorAll('.alert').forEach(function (alert) {
        setTimeout(function () { alert.style.opacity = '0'; setTimeout(function () { alert.remove(); }, 300); }, 5000);
    });

    // ─── Modal triggers ───────────────────────────────────────────────
    function openModal(id)  { const m = document.getElementById(id); if (m) { m.classList.add('open'); document.body.style.overflow = 'hidden'; } }
    function closeModal(id) { const m = document.getElementById(id); if (m) { m.classList.remove('open'); document.body.style.overflow = ''; } }

    document.querySelectorAll('[data-open-modal]').forEach(function (el) {
        el.addEventListener('click', function () { openModal(this.dataset.openModal); });
    });
    document.querySelectorAll('[data-close-modal]').forEach(function (el) {
        el.addEventListener('click', function () { closeModal(this.dataset.closeModal); });
    });

    window.openModal  = openModal;
    window.closeModal = closeModal;
});
