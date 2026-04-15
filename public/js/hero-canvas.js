/* IberPiso — Hero Canvas: Halftone Wave */
(function () {
  'use strict';

  var canvas = document.getElementById('hero-canvas');
  if (!canvas) return;
  var ctx = canvas.getContext('2d');
  if (!ctx) return;

  var SPEED   = 0.016;
  var STEP    = 4.6;
  var GLOW    = 46;
  var BASE_Y  = 0.54;

  var W = 0, H = 0, t = 0;
  var mx = 0, my = 0, tmx = 0, tmy = 0;

  function onResize() {
    var p = canvas.parentElement;
    W = canvas.width  = p ? p.clientWidth  : window.innerWidth;
    H = canvas.height = p ? p.clientHeight : window.innerHeight;
  }

  function waveY(x, phase) {
    var n    = x / Math.max(W, 1);
    var auto = Math.sin(t * 0.55) * 0.22 + Math.cos(t * 0.18) * 0.12; // mezcla para drift continuo
    var mi   = (my / Math.max(H, 1)) - 0.5 + auto; // parallax vertical
    return H * (
      BASE_Y
      + Math.sin(n * 5.0  + t * 0.78 + phase + mi * 1.6)  * 0.095
      + Math.sin(n * 3.1  - t * 0.50 + phase * 0.55)      * 0.055
      + Math.sin(n * 9.8  + t * 0.95 + phase * 1.5)       * 0.024
    );
  }

  function strokeWave(opts) {
    ctx.save();
    ctx.lineWidth   = opts.width;
    ctx.strokeStyle = opts.color;
    ctx.shadowColor = opts.shadowColor || 'transparent';
    ctx.shadowBlur  = opts.shadowBlur || 0;
    ctx.beginPath();
    for (var x = 0; x <= W + STEP; x += STEP) {
      var y = waveY(x, opts.phase || 0);
      if (x === 0) ctx.moveTo(x, y);
      else ctx.lineTo(x, y);
    }
    ctx.stroke();
    ctx.restore();
  }

  function draw() {
    ctx.imageSmoothingEnabled = true;
    ctx.clearRect(0, 0, W, H);

    var bg = ctx.createLinearGradient(0, 0, 0, H);
    bg.addColorStop(0, '#d9e5ff');
    bg.addColorStop(1, '#f7faff');
    ctx.fillStyle = bg;
    ctx.fillRect(0, 0, W, H);

    var glowGrad = ctx.createLinearGradient(0, H * 0.2, 0, H * 0.9);
    glowGrad.addColorStop(0, 'rgba(12,82,255,0.72)');
    glowGrad.addColorStop(1, 'rgba(46,145,255,0.45)');

    strokeWave({
      width: 30,
      phase: 0,
      color: glowGrad,
      shadowColor: 'rgba(12,72,255,0.32)',
      shadowBlur: GLOW
    });

    var mainGrad = ctx.createLinearGradient(0, H * 0.3, 0, H * 0.8);
    mainGrad.addColorStop(0, '#0b3be0');
    mainGrad.addColorStop(1, '#1aa3ff');

    strokeWave({
      width: 5.2,
      phase: 0.6,
      color: mainGrad,
      shadowColor: 'rgba(12,72,255,0.52)',
      shadowBlur: GLOW * 1.0
    });

    strokeWave({
      width: 3.2,
      phase: -0.35,
      color: 'rgba(255,255,255,0.98)',
      shadowColor: 'rgba(46,145,255,0.38)',
      shadowBlur: GLOW * 0.8
    });

    strokeWave({
      width: 1.6,
      phase: 0.15,
      color: 'rgba(6,42,140,0.55)',
      shadowColor: 'rgba(6,42,140,0.35)',
      shadowBlur: GLOW * 0.5
    });
  }

  function tick() {
    requestAnimationFrame(tick);
    t  += SPEED;
    mx += (tmx - mx) * 0.08;
    my += (tmy - my) * 0.08;
    draw();
  }

  onResize();

  var ro = new ResizeObserver(onResize);
  ro.observe(canvas.parentElement || document.body);

  window.addEventListener('mousemove', function (e) {
    tmx = e.clientX; tmy = e.clientY;
  }, { passive: true });

  requestAnimationFrame(tick);

  requestAnimationFrame(function () {
    requestAnimationFrame(function () {
      canvas.style.opacity = '1';
    });
  });
}());
