/* ── IberPiso — Home Page JS ── */
document.addEventListener('DOMContentLoaded', function () {

    // ─── Search tab switcher ──────────────────────────────────────────
    var tabs           = Array.prototype.slice.call(document.querySelectorAll('.search-tab'));
    var operacionInput = document.getElementById('search-operacion');
    var heroForm       = document.getElementById('hero-search-form');

    function syncOperacion() {
        var active = document.querySelector('.search-tab.active');
        if (active && operacionInput) {
            operacionInput.value = active.dataset.op || 'venta';
        }
    }

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function (e) {
            e.preventDefault();
            tabs.forEach(function (t) { t.classList.remove('active'); });
            tab.classList.add('active');
            syncOperacion();
        });
    });

    if (heroForm) {
        heroForm.addEventListener('submit', syncOperacion);
    }

    syncOperacion();

    // ─── Animated counters ────────────────────────────────────────────
    function animateCount(el, target) {
        var start = 0;
        var duration = 1500;
        var step = target / (duration / 16);
        var current = start;
        var timer = setInterval(function () {
            current = Math.min(current + step, target);
            el.textContent = Math.floor(current).toLocaleString('es-ES');
            if (current >= target) clearInterval(timer);
        }, 16);
    }

    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                var el = entry.target;
                var target = parseInt(el.dataset.target, 10);
                if (!isNaN(target)) animateCount(el, target);
                observer.unobserve(el);
            }
        });
    }, { threshold: 0.5 });

    document.querySelectorAll('.stat-number[data-target]').forEach(function (el) {
        observer.observe(el);
    });
});
