/* ── IberPiso — Property Detail JS ── */
document.addEventListener('DOMContentLoaded', function () {
    var csrfToken = document.querySelector('meta[name="csrf-token"]');
    var csrf = csrfToken ? csrfToken.getAttribute('content') : '';

    // ─── Gallery thumbnails ───────────────────────────────────────────
    var mainPhoto = document.getElementById('main-photo');
    document.querySelectorAll('.gallery-thumb').forEach(function (thumb) {
        thumb.addEventListener('click', function () {
            if (mainPhoto) mainPhoto.src = this.dataset.full;
            document.querySelectorAll('.gallery-thumb').forEach(function (t) { t.classList.remove('active'); });
            this.classList.add('active');
        });
    });

    // ─── Lightbox ─────────────────────────────────────────────────────
    var lightbox   = document.getElementById('lightbox');
    var lbImg      = document.getElementById('lightbox-img');
    var openLbBtn  = document.getElementById('open-lightbox');
    var closeLbBtn = document.getElementById('lightbox-close-btn');
    var closeOverlay = document.getElementById('close-lightbox');
    var lbPrev     = document.getElementById('lb-prev');
    var lbNext     = document.getElementById('lb-next');
    var thumbs     = Array.from(document.querySelectorAll('.gallery-thumb'));
    var currentIdx = 0;

    function openLightbox(idx) {
        if (!lightbox || !lbImg || !thumbs.length) return;
        currentIdx = idx;
        lbImg.src = thumbs[idx].dataset.full;
        lightbox.classList.add('open');
        lightbox.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }
    function closeLightbox() {
        if (!lightbox) return;
        lightbox.classList.remove('open');
        lightbox.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }
    function showLbImage(idx) {
        if (!thumbs.length) return;
        currentIdx = (idx + thumbs.length) % thumbs.length;
        lbImg.src = thumbs[currentIdx].dataset.full;
    }

    if (openLbBtn) openLbBtn.addEventListener('click', function () { openLightbox(0); });
    if (closeLbBtn) closeLbBtn.addEventListener('click', closeLightbox);
    if (closeOverlay) closeOverlay.addEventListener('click', closeLightbox);
    if (lbPrev) lbPrev.addEventListener('click', function () { showLbImage(currentIdx - 1); });
    if (lbNext) lbNext.addEventListener('click', function () { showLbImage(currentIdx + 1); });

    document.addEventListener('keydown', function (e) {
        if (!lightbox || !lightbox.classList.contains('open')) return;
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft')  showLbImage(currentIdx - 1);
        if (e.key === 'ArrowRight') showLbImage(currentIdx + 1);
    });

    // ─── Contact form AJAX ────────────────────────────────────────────
    var inquiryForm = document.getElementById('inquiry-form');
    if (inquiryForm) {
        inquiryForm.addEventListener('submit', function (e) {
            e.preventDefault();
            var btn = document.getElementById('inquiry-submit');
            var response = document.getElementById('inquiry-response');
            var propertyId = inquiryForm.dataset.propertyId;

            btn.disabled = true;
            btn.textContent = 'Enviando…';

            var data = new FormData(inquiryForm);

            fetch('/propiedades/' + propertyId + '/contactar', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: data,
            })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (res.success) {
                    inquiryForm.reset();
                    response.innerHTML = '<div class="alert alert-success">' + res.message + '</div>';
                } else {
                    var errors = res.errors ? Object.values(res.errors).flat().join('<br>') : 'Error al enviar.';
                    response.innerHTML = '<div class="alert alert-error">' + errors + '</div>';
                }
            })
            .catch(function () {
                response.innerHTML = '<div class="alert alert-error">Error de conexión. Inténtalo de nuevo.</div>';
            })
            .finally(function () {
                btn.disabled = false;
                btn.textContent = 'Enviar consulta';
            });
        });
    }

    // ─── Mobile contact button ────────────────────────────────────────
    var mobileContactBtn = document.getElementById('mobile-contact-btn');
    if (mobileContactBtn) {
        mobileContactBtn.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
});
