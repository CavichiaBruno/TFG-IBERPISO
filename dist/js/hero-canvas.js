/* IberPiso — Hero Canvas: Halftone Wave */
(function () {
  'use strict';

  var canvas = document.getElementById('hero-canvas');
  if (!canvas) return;
  var ctx = canvas.getContext('2d');
  if (!ctx) return;

  var SPEED   = 0.008; 
  var STEP    = 6;     // Paso más grande para mejor performance
  var BASE_Y  = 0.60;

  var W = 0, H = 0, t = 0;

  function onResize() {
    var p = canvas.parentElement;
    W = canvas.width  = (p ? p.clientWidth  : window.innerWidth);
    H = canvas.height = (p ? p.clientHeight : window.innerHeight);
  }

  function waveY(x, phase) {
    var n = x / Math.max(W, 1);
    var v = t * 1.5;
    return H * (
      BASE_Y
      + Math.sin(n * 3.2 + v + phase) * 0.06
      + Math.sin(n * 1.8 - v * 0.6 + phase * 0.5) * 0.03
    );
  }

  function draw() {
    ctx.clearRect(0, 0, W, H);

    // Fondo degradado suave (Muy rápido)
    var bg = ctx.createLinearGradient(0, 0, 0, H);
    bg.addColorStop(0, '#f5f9ff');
    bg.addColorStop(1, '#ffffff');
    ctx.fillStyle = bg;
    ctx.fillRect(0, 0, W, H);

    // 1. DIBUJAR CAPAS DE OLAS (STROKES SIN SOMBRAS = MUY RÁPIDO)
    function drawLayer(width, color, phase, alpha) {
        ctx.save();
        ctx.globalAlpha = alpha;
        ctx.lineWidth = width;
        ctx.strokeStyle = color;
        ctx.lineCap = 'round';
        ctx.beginPath();
        for (var x = 0; x <= W + STEP; x += STEP) {
            var y = waveY(x, phase);
            if (x === 0) ctx.moveTo(x, y);
            else ctx.lineTo(x, y);
        }
        ctx.stroke();
        ctx.restore();
    }

    // Capas glassy optimizadas
    drawLayer(60, 'rgba(0,100,255,0.1)', 0, 0.4);
    drawLayer(15, 'rgba(0,74,172,0.2)', 0.5, 0.6);
    drawLayer(4,  'rgba(0,100,255,0.7)', 0.8, 0.8);
    drawLayer(2,  'rgba(255,255,255,0.9)', 0.82, 1.0);

    // 2. EFECTO HALFTONE (PUNTOS) — Optimizado al máximo
    // Dibujamos una rejilla de puntos que se desplazan levemente con la onda
    var dotSpacing = 24;
    ctx.fillStyle = 'rgba(0,74,172,0.15)';
    for (var ix = 0; ix <= W; ix += dotSpacing) {
        var wy = waveY(ix, 0.5);
        for (var iy = Math.floor(wy); iy < H; iy += dotSpacing) {
            // Factor de opacidad basado en cercanía a la superficie
            var opacity = 1 - Math.min((iy - wy) / (H * 0.3), 1);
            if (opacity <= 0) continue;
            
            ctx.globalAlpha = opacity * 0.6;
            // Usar fillRect es X10 veces más rápido que arc para miles de puntos
            ctx.fillRect(ix, iy, 1.5, 1.5);
        }
    }
    ctx.globalAlpha = 1;
  }

  function tick() {
    t += SPEED;
    draw();
    requestAnimationFrame(tick);
  }

  onResize();
  var ro = new ResizeObserver(onResize);
  ro.observe(canvas.parentElement || document.body);
  requestAnimationFrame(tick);

  // Fade in
  canvas.style.transition = 'opacity 1.5s ease-out';
  requestAnimationFrame(function() { canvas.style.opacity = '1'; });
}());

