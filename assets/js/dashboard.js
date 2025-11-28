document.addEventListener("DOMContentLoaded", () => {
    const canvas = document.getElementById('chartReservas');
    const filtroAnio = document.getElementById('filtro-anio');
    const ctx = canvas.getContext('2d');
    const reservasPorMes = JSON.parse(canvas.dataset.reservas);

    let chartInstance = null;

    function actualizarGrafico(datos) {
        if (chartInstance) {
            chartInstance.destroy();
        }

        chartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: datos.map(r => r.mes_nombre),
                datasets: [{
                    label: 'Reservas',
                    data: datos.map(r => r.cantidad),
                    backgroundColor: 'rgba(76, 175, 80, 0.7)',
                    borderColor: 'rgba(76, 175, 80, 1)',
                    borderWidth: 1,
                    borderRadius: 6,
                    barPercentage: 0.1,
                    categoryPercentage: 0.6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.parsed.y} reservas`;
                            }
                        }
                    },
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 0,
                            minRotation: 0
                        }
                    }
                }
            }
        });
    }
    

    filtroAnio.addEventListener('change', (event) => {
        const anioSeleccionado = event.target.value;
        const reservasFiltradas = reservasPorMes.filter(r => r.mes.startsWith(anioSeleccionado));
        actualizarGrafico(reservasFiltradas);
    });

    const anioInicial = filtroAnio.value;
    const reservasFiltradas = reservasPorMes.filter(r => r.mes.startsWith(anioInicial));
    actualizarGrafico(reservasFiltradas);
});
