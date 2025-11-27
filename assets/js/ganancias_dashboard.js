const ctx = document.getElementById('chartGananciasPorMes').getContext('2d');
const gananciasData = JSON.parse(document.getElementById('chartGananciasPorMes').getAttribute('data-ganancias'));

const chartGananciasPorMes = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: gananciasData.map(data => data.mes),
        datasets: [{
            label: 'Ganancia Neta',
            data: gananciasData.map(data => data.ganancia_neta),
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
