/**
 * IberPiso — Lógica para la página de listado de propiedades
 * Maneja filtros, cambio de vista (cuadrícula/lista) y carga asíncrona.
 */

document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('filters-form');
    var sortSelect = document.getElementById('sort-select');
    var resultsContainer = document.getElementById('results-container');
    var paginationContainer = document.getElementById('pagination-container');
    var resultsCount = document.querySelector('.results-count strong');

    /**
     * Carga los resultados usando AJAX para no recargar la página completa
     */
    function loadResults(url) {
        if (!resultsContainer) return;
        resultsContainer.style.opacity = '0.5';

        // Añadimos un parámetro para que el servidor sepa que es una petición AJAX
        var fetchUrl = url + (url.includes('?') ? '&' : '?') + '_ajax=1';
        fetch(fetchUrl, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            resultsContainer.innerHTML = data.html;
            if (paginationContainer) paginationContainer.innerHTML = data.pagination || '';
            if (resultsCount) resultsCount.textContent = data.total;
            resultsContainer.style.opacity = '1';
            window.scrollTo({ top: 0, behavior: 'smooth' }); // Volvemos arriba suavemente
        })
        .catch(() => { resultsContainer.style.opacity = '1'; });
    }

    // Cambio de ordenación (Precio, superficie, etc.)
    if (sortSelect) {
        sortSelect.addEventListener('change', function () {
            if (!form) return;
            var params = new URLSearchParams(new FormData(form));
            params.set('orden', this.value);
            window.location.href = window.location.pathname + '?' + params.toString();
        });
    }

    // Botones tipo "Pill" para filtros rápidos (Operación, Habitaciones...)
    document.querySelectorAll('.pill').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var name = this.dataset.name;
            document.querySelectorAll('.pill[data-name="' + name + '"]').forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            var hidden = document.getElementById(name + '-val');
            if (hidden) hidden.value = this.dataset.value;
        });
    });

    // Alternar entre vista de Cuadrícula (Grid) y Lista (List)
    var viewGrid = document.getElementById('view-grid');
    var viewList = document.getElementById('view-list');
    var grid = document.getElementById('results-container');

    if (viewGrid && viewList && grid) {
        viewGrid.addEventListener('click', () => {
            grid.classList.remove('list-view');
            viewGrid.classList.add('active');
            viewList.classList.remove('active');
        });
        viewList.addEventListener('click', () => {
            grid.classList.add('list-view');
            viewList.classList.add('active');
            viewGrid.classList.remove('active');
        });
    }

    // Panel de filtros lateral en dispositivos móviles
    var mobileFilterBtn = document.getElementById('mobile-filter-btn');
    var filtersSidebar = document.getElementById('filters-sidebar');

    if (mobileFilterBtn && filtersSidebar) {
        mobileFilterBtn.addEventListener('click', () => {
            filtersSidebar.classList.toggle('open');
            document.body.style.overflow = filtersSidebar.classList.contains('open') ? 'hidden' : '';
        });
    }
});
