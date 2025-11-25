document.addEventListener('DOMContentLoaded', function() {

    function parseReservasData(raw) {
        if (!raw) return { labels: [], values: [] };
        try {
            const arr = typeof raw === 'string' ? JSON.parse(raw) : raw;
            arr.sort((a,b) => (a.mes > b.mes) ? 1 : -1);
            const labels = arr.map(it => {
                const parts = it.mes.split('-');
                if (parts.length === 2) {
                    const d = new Date(parts[0], parts[1]-1, 1);
                    return d.toLocaleString(undefined, { month: 'short', year: 'numeric' });
                }
                return it.mes;
            });
            const values = arr.map(it => Number(it.total || it.cantidad || 0));
            return { labels, values };
        } catch (e) {
            console.error('Error parseando reservas data', e);
            return { labels: [], values: [] };
        }
    }

    function parseReservasPorTour(raw) {
        if (!raw) return { labels: [], values: [] };
        try {
            const arr = typeof raw === 'string' ? JSON.parse(raw) : raw;
            const labels = arr.map(it => it.tour || 'Tour');
            const values = arr.map(it => Number(it.total || 0));
            return { labels, values };
        } catch (e) {
            console.error('Error parseando reservas por tour', e);
            return { labels: [], values: [] };
        }
    }

    const canvasReservas = document.getElementById('chartReservas');
    if (canvasReservas) {
        const raw = canvasReservas.dataset.reservas;
        const parsed = parseReservasData(raw);

        const ctx = canvasReservas.getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: parsed.labels,
                datasets: [{
                    label: 'Reservas confirmadas',
                    data: parsed.values,
                    borderWidth: 1,
                    barPercentage: 0.1,
                    categoryPercentage: 0.6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: { mode: 'index', intersect: false }
                }
            }
        });
    }

    const canvasTours = document.getElementById('chartOcupacion');
    if (canvasTours) {
        const raw = canvasTours.dataset.tipos;
        const parsed = parseReservasPorTour(raw);

        const ctx2 = canvasTours.getContext('2d');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: parsed.labels,
                datasets: [{
                    label: 'Total reservas',
                    data: parsed.values,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: { mode: 'nearest' }
                }
            }
        });
    }

    window.dashboardTour = {
        refreshNotifications: function() {
            console.log('refreshNotifications - no implementado aún');
        }
    };

});
