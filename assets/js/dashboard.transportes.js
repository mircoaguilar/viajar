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

    function parseOcupacionTipo(raw) {
        if (!raw) return { labels: [], values: [] };
        try {
            const arr = typeof raw === 'string' ? JSON.parse(raw) : raw;
            const labels = arr.map(it => it.tipo || it.nombre || 'Tipo');
            const values = arr.map(it => Number(it.ocupadas || it.total || 0));
            return { labels, values };
        } catch (e) {
            console.error('Error parseando ocupacion data', e);
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
                    backgroundColor: '#4CAF50', 
                    borderRadius: 4,
                    barPercentage: 0.5,
                    categoryPercentage: 0.7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { mode: 'index', intersect: false }
                },
                scales: {
                    x: {
                        grid: { display: false }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                }
            }
        });
    }

    const canvasOcup = document.getElementById('chartOcupacion');
    if (canvasOcup) {
        const raw = canvasOcup.dataset.tipos;
        const parsed = parseOcupacionTipo(raw);

        const ctx2 = canvasOcup.getContext('2d');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: parsed.labels,
                datasets: [{
                    label: 'Ocupación',
                    data: parsed.values,
                    backgroundColor: [
                        '#4CAF50',
                        '#2196F3',
                        '#FFC107',
                        '#FF5722',
                        '#9C27B0',
                        '#00BCD4'
                    ]
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

    window.dashboardTransporte = {
        refreshNotifications: function() {
            console.log('refreshNotifications - no implementado aún');
        }
    };

});
