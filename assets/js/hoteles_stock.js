document.addEventListener('DOMContentLoaded', () => {

    const hotelSelect = document.getElementById('rela_hotel');
    const habitacionSelect = document.getElementById('rela_habitacion');
    const form = document.getElementById('form-stock');
    const btnPrev = document.getElementById('previsualizar');
    const previewTable = document.getElementById('tabla-preview');
    const tbody = previewTable.querySelector('tbody');
    const alertBox = document.getElementById('form-alert');
    const HAB_URL = idHabitacionUrl || "";    
    const HOTEL_PRE = hotelPreseleccionado || "";

    function cargarHabitaciones(idHotel, callback = null) {
        habitacionSelect.innerHTML = '<option value="">Cargando...</option>';

        fetch('controllers/habitaciones/habitaciones.controlador.php?action=traer_por_hotel&id_hotel=' + idHotel)
            .then(res => res.json())
            .then(data => {
                habitacionSelect.innerHTML = '<option value="">Seleccionar habitación...</option>';

                data.forEach(hab => {
                    const option = document.createElement('option');
                    option.value = hab.id_hotel_habitacion;
                    option.textContent = hab.tipo_nombre + ' - Capacidad ' + hab.capacidad_maxima;
                    habitacionSelect.appendChild(option);
                });

                if (typeof callback === "function") callback();
            });
    }

    hotelSelect.addEventListener('change', () => {
        const idHotel = hotelSelect.value;
        if (idHotel) {
            cargarHabitaciones(idHotel);
        } else {
            habitacionSelect.innerHTML = '<option value="">Seleccionar habitación...</option>';
        }
    });

    if (HOTEL_PRE !== "") {
        hotelSelect.value = HOTEL_PRE;
        cargarHabitaciones(HOTEL_PRE, () => {
            if (HAB_URL !== "") {
                habitacionSelect.value = HAB_URL;
            }
        });
    }

    function validarFormulario() {
        let valido = true;
        alertBox.style.display = 'none';
        alertBox.textContent = '';
        alertBox.className = '';

        const fields = [
            { el: hotelSelect, msg: 'Debe seleccionar un hotel' },
            { el: habitacionSelect, msg: 'Debe seleccionar una habitación' },
            { el: form.fecha_inicio, msg: 'Debe ingresar fecha de inicio' },
            { el: form.fecha_fin, msg: 'Debe ingresar fecha fin' },
            { el: form.cantidad, msg: 'Cantidad inválida' }
        ];

        fields.forEach(f => {
            const parent = f.el.parentElement;
            let errorDiv = parent.querySelector('.error');
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.className = 'error';
                parent.appendChild(errorDiv);
            }
            errorDiv.textContent = '';
            f.el.classList.remove('is-invalid');

            if (!f.el.value || (f.el.name === 'cantidad' && parseInt(f.el.value, 10) < 0)) {
                errorDiv.textContent = f.msg;
                f.el.classList.add('is-invalid');
                valido = false;
            }
        });

        if (!valido) {
            alertBox.textContent = 'Corrija los errores en los campos resaltados.';
            alertBox.className = 'error';
            alertBox.style.display = 'block';
        }

        return valido;
    }

    const inputs = form.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.addEventListener('input', () => {
            input.classList.remove('is-invalid');
            const errorDiv = input.parentElement.querySelector('.error');
            if (errorDiv) errorDiv.textContent = '';
            alertBox.style.display = 'none';
        });
    });

    btnPrev.addEventListener('click', () => {
        tbody.innerHTML = '';
        if (!validarFormulario()) return;

        const habText = habitacionSelect.options[habitacionSelect.selectedIndex]?.text || '';
        const fechaInicio = form.fecha_inicio.value;
        const fechaFin = form.fecha_fin.value;
        const cantidad = parseInt(form.cantidad.value, 10);

        const start = new Date(fechaInicio);
        const end = new Date(fechaFin);
        end.setDate(end.getDate() + 1);

        for (let d = new Date(start); d < end; d.setDate(d.getDate() + 1)) {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${habText}</td>
                <td>${d.toISOString().slice(0, 10)}</td>
                <td>${cantidad}</td>
            `;
            tbody.appendChild(tr);
        }
        previewTable.style.display = 'table';
    });

    form.addEventListener('submit', e => {
        e.preventDefault();
        if (!validarFormulario()) return;

        const formData = new FormData(form);

        fetch('controllers/hoteles/hotel_habitaciones_stock.controlador.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                alertBox.textContent = data.message || 'Stock guardado correctamente.';
                alertBox.className = 'success';
                alertBox.style.display = 'block';

                form.reset();
                tbody.innerHTML = '';
                previewTable.style.display = 'none';
            } else {
                alertBox.textContent = data.message || 'Error al guardar stock.';
                alertBox.className = 'error';
                alertBox.style.display = 'block';
            }
        })
        .catch(err => {
            console.error(err);
            alertBox.textContent = 'Error en la conexión con el servidor.';
            alertBox.className = 'error';
            alertBox.style.display = 'block';
        });
    });
});
