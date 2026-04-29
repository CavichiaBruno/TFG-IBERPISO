/**
 * Basic Hero Canvas animation
 */
document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('hero-canvas');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    let width, height;

    function resize() {
        width = canvas.width = window.innerWidth;
        height = canvas.height = window.innerHeight;
    }

    window.addEventListener('resize', resize);
    resize();

    // Just a very subtle gradient or some dots to show it's working
    function draw() {
        ctx.clearRect(0, 0, width, height);
        // Add subtle animation logic here if needed
        requestAnimationFrame(draw);
    }

    // draw(); // Disabled for now to keep it lightweight as requested
});
