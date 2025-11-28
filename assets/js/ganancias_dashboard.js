document.addEventListener("DOMContentLoaded", () => {
    const canvas = document.getElementById('chartGananciasPorMes');
    const gananciasPorMes = JSON.parse(canvas.dataset.ganancias);

    const labels = gananciasPorMes.map(g => {
        const [year, month] = g.mes.split('-'); 
        const fecha = new Date(year, month - 1); 
        return fecha.toLocaleString('es-AR', { month: 'long', year: 'numeric' }); 
    });

    const data = gananciasPorMes.map(g => parseFloat(g.ganancia_neta));

    const ctx = canvas.getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Ganancia Neta ($)',
                data,
                backgroundColor: 'rgba(33, 150, 243, 0.6)',
                borderColor: 'rgba(33, 150, 243, 1)',
                borderWidth: 1,
                borderRadius: 6,
                barPercentage: 0.5,
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
                            return `$${context.parsed.y.toLocaleString('es-AR', {minimumFractionDigits: 2})}`;
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
                        callback: function(value) {
                            return `$${value.toLocaleString('es-AR', {minimumFractionDigits: 2})}`;
                        }
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
