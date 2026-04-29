/* ── IberPiso — Listing Page JS ── */
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('filters-form');
    var sortSelect = document.getElementById('sort-select');
    var resultsContainer = document.getElementById('results-container');
    var paginationContainer = document.getElementById('pagination-container');
    var resultsCount = document.querySelector('.results-count strong');

    // ─── AJAX filter submit ───────────────────────────────────────────
    function loadResults(url) {
        if (!resultsContainer) return;
        resultsContainer.style.opacity = '0.5';

        var fetchUrl = url + (url.includes('?') ? '&' : '?') + '_ajax=1';
        fetch(fetchUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            resultsContainer.innerHTML = data.html;
            if (paginationContainer) paginationContainer.innerHTML = data.pagination || '';
            if (resultsCount) resultsCount.textContent = data.total;
            resultsContainer.style.opacity = '1';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        })
        .catch(function () { resultsContainer.style.opacity = '1'; });
    }

    if (sortSelect) {
        sortSelect.addEventListener('change', function () {
            if (!form) return;
            var params = new URLSearchParams(new FormData(form));
            params.set('orden', this.value);
            window.location.href = window.location.pathname + '?' + params.toString();
        });
    }

    // ─── Pill buttons ─────────────────────────────────────────────────
    document.querySelectorAll('.pill').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var name = this.dataset.name;
            var value = this.dataset.value;
            var group = document.querySelectorAll('.pill[data-name="' + name + '"]');
            group.forEach(function (p) { p.classList.remove('active'); });
            this.classList.add('active');
            var hidden = document.getElementById(name + '-val');
            if (hidden) hidden.value = value;
        });
    });

    // ─── View toggle ──────────────────────────────────────────────────
    var viewGrid = document.getElementById('view-grid');
    var viewList = document.getElementById('view-list');
    var grid = document.getElementById('results-container');

    if (viewGrid && viewList && grid) {
        viewGrid.addEventListener('click', function () {
            grid.classList.remove('list-view');
            viewGrid.classList.add('active');
            viewList.classList.remove('active');
        });
        viewList.addEventListener('click', function () {
            grid.classList.add('list-view');
            viewList.classList.add('active');
            viewGrid.classList.remove('active');
        });
    }

    // ─── Mobile filter panel ──────────────────────────────────────────
    var mobileFilterBtn = document.getElementById('mobile-filter-btn');
    var filtersSidebar = document.getElementById('filters-sidebar');

    if (mobileFilterBtn && filtersSidebar) {
        mobileFilterBtn.addEventListener('click', function () {
            filtersSidebar.classList.toggle('open');
            document.body.style.overflow = filtersSidebar.classList.contains('open') ? 'hidden' : '';
        });
    }
});
