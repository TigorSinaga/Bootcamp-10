/**
 * Dashboard Admin — JavaScript
 * Depends on: Chart.js (loaded via app.js → window.Chart)
 */

const initializeDashboard = () => {
    if (!document.querySelector('.db-page')) return;

    initLiveClock();
    initCountUp();
    initOrdersChart();
    initStatusChart();
};

/* ── Live clock ─────────────────────────────────── */
const initLiveClock = () => {
    const el = document.getElementById('live-clock');
    if (!el) return;

    const update = () => {
        const now = new Date();
        el.textContent =
            now.toLocaleDateString('id-ID', {
                weekday: 'long', day: 'numeric',
                month: 'long', year: 'numeric',
            }) +
            ' · ' +
            now.toLocaleTimeString('id-ID', {
                hour: '2-digit', minute: '2-digit', second: '2-digit',
            });
    };

    update();
    setInterval(update, 1000);
};

/* ── Count-up animation ─────────────────────────── */
const initCountUp = () => {
    const easeOut = t => 1 - Math.pow(1 - t, 3);

    document.querySelectorAll('.count-up').forEach(el => {
        const target   = parseInt(el.dataset.target ?? '0', 10);
        const duration = 1200;
        const start    = performance.now();

        const tick = now => {
            const t   = Math.min((now - start) / duration, 1);
            const val = Math.round(easeOut(t) * target);
            el.textContent = val.toLocaleString('id-ID');
            if (t < 1) requestAnimationFrame(tick);
        };

        requestAnimationFrame(tick);
    });
};

/* ── Orders per month — Bar chart ───────────────── */
const initOrdersChart = () => {
    const canvas = document.getElementById('ordersChart');
    if (!canvas || !window.Chart) return;

    const labels = JSON.parse(canvas.dataset.labels ?? '[]');
    const values = JSON.parse(canvas.dataset.values ?? '[]');

    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color       = '#6b7280';

    new window.Chart(canvas, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Pesanan',
                data: values,
                backgroundColor: ctx => {
                    const g = ctx.chart.ctx.createLinearGradient(0, 0, 0, 260);
                    g.addColorStop(0, 'rgba(99,102,241,0.85)');
                    g.addColorStop(1, 'rgba(99,102,241,0.15)');
                    return g;
                },
                borderRadius:  8,
                borderSkipped: false,
                borderWidth:   0,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1a1d2e',
                    padding:         10,
                    cornerRadius:    10,
                    callbacks: { label: ctx => ` ${ctx.parsed.y} pesanan` },
                },
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 11 } } },
                y: {
                    grid: { color: '#f0f2f9', drawBorder: false },
                    ticks: { precision: 0, font: { size: 11 } },
                    beginAtZero: true,
                },
            },
        },
    });
};

/* ── Order status — Doughnut chart ──────────────── */
const initStatusChart = () => {
    const canvas = document.getElementById('statusChart');
    if (!canvas || !window.Chart) return;

    const labels = JSON.parse(canvas.dataset.labels ?? '[]');
    const values = JSON.parse(canvas.dataset.values ?? '[]');
    const colors = ['#f59e0b', '#3b82f6', '#10b981', '#ef4444'];

    new window.Chart(canvas, {
        type: 'doughnut',
        data: {
            labels,
            datasets: [{
                data: values,
                backgroundColor: colors,
                borderWidth:     3,
                borderColor:     '#ffffff',
                hoverOffset:     6,
            }],
        },
        options: {
            responsive:  true,
            cutout:      '68%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1a1d2e',
                    padding:         10,
                    cornerRadius:    10,
                    callbacks: { label: ctx => ` ${ctx.label}: ${ctx.parsed} pesanan` },
                },
            },
        },
    });

    /* Custom legend */
    const legend = document.getElementById('status-legend');
    if (!legend) return;

    labels.forEach((label, i) => {
        const item = document.createElement('div');
        item.className = 'db-status-legend-item';
        item.innerHTML =
            `<span class="db-status-legend-dot" style="background:${colors[i]};"></span>` +
            `${label} <strong style="color:#1a1d2e;">(${values[i]})</strong>`;
        legend.appendChild(item);
    });
};

/* ── Boot ───────────────────────────────────────── */
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeDashboard, { once: true });
} else {
    initializeDashboard();
}

