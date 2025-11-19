document.querySelectorAll('.ver-stock-btn').forEach(btn => {
    btn.addEventListener('click', function(){
        const idHabitacion = this.dataset.idHabitacion;
        
        fetch('controllers/hoteles/hotel_habitaciones_stock.controlador.php', {
            method: 'POST',
            headers: { 'Content-Type':'application/x-www-form-urlencoded' },
            body: 'action=traer_stock&id_habitacion=' + idHabitacion
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success'){
                const tbody = document.querySelector('#stock-table tbody');
                tbody.innerHTML = '';
                data.stock.forEach(s => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `<td>${s.fecha}</td><td>${s.cantidad_disponible}</td>`;
                    tbody.appendChild(tr);
                });
                document.getElementById('modal-stock').style.display = 'flex';
            } else {
                alert(data.message);
            }
        });
    });
});

document.querySelector('#modal-stock .close').addEventListener('click', function(){
    document.getElementById('modal-stock').style.display = 'none';
});