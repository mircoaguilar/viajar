document.addEventListener("DOMContentLoaded", () => {
    const canvas = document.getElementById('chartReservas');
    const reservasPorMes = JSON.parse(canvas.dataset.reservas);

    const labels = reservasPorMes.map(r => r.mes_nombre);
    const data = reservasPorMes.map(r => r.cantidad);

    const ctx = canvas.getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Reservas',
                data,
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
});
