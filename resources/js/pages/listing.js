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
        
        // Si no hay HTML previo, mostramos esqueletos
        if (resultsContainer.innerHTML.trim() === '' || resultsContainer.querySelector('.skeleton-card')) {
            // Ya hay esqueletos o está vacío, no hacemos nada más que asegurar que se vean
        } else {
            resultsContainer.style.opacity = '0.5';
        }

        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            resultsContainer.style.opacity = '1';
            resultsContainer.innerHTML = data.html;
            if (resultsCount) resultsCount.textContent = data.count || '0';
            
            // Re-vincular eventos si es necesario (ej. para favoritos si los hubiera en la tarjeta)
            window.scrollTo({ top: 0, behavior: 'smooth' });
        })
        .catch(err => { 
            console.error('Error loading properties:', err);
            resultsContainer.style.opacity = '1'; 
        });
    }

    // Carga inicial automática
    if (resultsContainer && resultsContainer.dataset.autoload === 'true') {
        loadResults(window.location.href);
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
    var closeFiltersBtn = document.getElementById('close-filters');
    var filtersSidebar = document.getElementById('filters-sidebar');

    if (mobileFilterBtn && filtersSidebar) {
        mobileFilterBtn.addEventListener('click', () => {
            filtersSidebar.classList.add('open');
            document.body.style.overflow = 'hidden';
        });
    }

    if (closeFiltersBtn && filtersSidebar) {
        closeFiltersBtn.addEventListener('click', () => {
            filtersSidebar.classList.remove('open');
            document.body.style.overflow = '';
        });
    }
});
