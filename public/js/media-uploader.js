/* ── IberPiso — Media Uploader ── */
document.addEventListener('DOMContentLoaded', function () {
    var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function setupUploadZone(zoneId, inputId, type) {
        var zone  = document.getElementById(zoneId);
        var input = document.getElementById(inputId);
        if (!zone || !input) return;

        var propertyId = zone.dataset.property;
        var uploadUrl  = window.MEDIA_STORE_URL;

        // Click to select
        zone.addEventListener('click', function (e) {
            if (e.target.tagName !== 'LABEL') input.click();
        });

        // Drag & Drop
        zone.addEventListener('dragover',  function (e) { e.preventDefault(); zone.classList.add('drag-over'); });
        zone.addEventListener('dragleave', function () { zone.classList.remove('drag-over'); });
        zone.addEventListener('drop', function (e) {
            e.preventDefault();
            zone.classList.remove('drag-over');
            uploadFiles(e.dataTransfer.files, type);
        });

        input.addEventListener('change', function () {
            uploadFiles(this.files, type);
            this.value = '';
        });

        function uploadFiles(files, fileType) {
            Array.from(files).forEach(function (file) {
                var formData = new FormData();
                formData.append('file', file);
                formData.append('tipo_archivo', fileType === 'image' ? 'imagen' : fileType);

                fetch(uploadUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: formData,
                })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    if (res.success) {
                        if (fileType === 'image') addImagePreview(res.media);
                        else addDocPreview(res.media);
                    } else {
                        var errorMsg = res.error || res.message || 'Error al subir archivo.';
                        if (res.errors) {
                            errorMsg += '\n' + Object.values(res.errors).flat().join('\n');
                        }
                        alert(errorMsg);
                    }
                })
                .catch(function () { alert('Error de conexión.'); });
            });
        }
    }

    function addImagePreview(media) {
        var grid = document.getElementById('images-grid');
        if (!grid) return;
        var div = document.createElement('div');
        div.className = 'media-item';
        div.dataset.id = media.id;
        div.innerHTML =
            '<img src="' + media.url + '" alt="Imagen" loading="lazy">' +
            (media.es_portada ? '<span class="cover-badge">Portada</span>' : '') +
            '<div class="media-actions">' +
            '<button type="button" class="set-cover-btn" data-id="' + media.id + '" title="Portada">★</button>' +
            '<button type="button" class="delete-media-btn" data-id="' + media.id + '" title="Eliminar">✕</button>' +
            '</div>';
        grid.appendChild(div);
        bindMediaActions(div);
    }

    function addDocPreview(media) {
        var list = document.getElementById('docs-list');
        if (!list) return;
        var div = document.createElement('div');
        div.className = 'doc-item-admin';
        div.dataset.id = media.id;
        div.innerHTML =
            '<svg viewBox="0 0 24 24" width="18" height="18"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor" fill="none" stroke-width="2"/></svg>' +
            '<span>' + media.nombre_original + '</span>' +
            '<button type="button" class="delete-media-btn" data-id="' + media.id + '">✕</button>';
        list.appendChild(div);
        bindMediaActions(div);
    }

    function bindMediaActions(container) {
        var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        container.querySelectorAll('.delete-media-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var id  = this.dataset.id;
                var item = this.closest('.media-item, .doc-item-admin');
                var baseUrl = window.MEDIA_BASE_URL.replace('__ID__', id);

                fetch(baseUrl, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                })
                .then(function (r) { return r.json(); })
                .then(function (res) { if (res.success && item) item.remove(); });
            });
        });

        container.querySelectorAll('.set-cover-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var id = this.dataset.id;
                var baseUrl = window.COVER_BASE_URL.replace('__ID__', id);

                fetch(baseUrl, {
                    method: 'PATCH',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    if (res.success) {
                        document.querySelectorAll('.set-cover-btn').forEach(function (b) { b.classList.remove('cover-active'); });
                        document.querySelectorAll('.cover-badge').forEach(function (b) { b.remove(); });
                        btn.classList.add('cover-active');
                        var item = btn.closest('.media-item');
                        if (item && !item.querySelector('.cover-badge')) {
                            var badge = document.createElement('span');
                            badge.className = 'cover-badge'; badge.textContent = 'Portada';
                            item.appendChild(badge);
                        }
                    }
                });
            });
        });
    }

    // Bind existing media items
    document.querySelectorAll('.media-item, .doc-item-admin').forEach(bindMediaActions);

    // Setup upload zones
    setupUploadZone('image-upload-zone', 'image-input', 'image');
    setupUploadZone('pdf-upload-zone',   'pdf-input',   'pdf');
});
