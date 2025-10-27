const notificationsBtn = document.getElementById('notifications');
const notificationsDropdown = document.getElementById('notifications-dropdown');
const notificationsList = document.getElementById('notifications-list');
const notificationCount = document.querySelector('.notification-count');
const markAllBtn = document.getElementById('mark-all-read');


notificationsBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    const isVisible = notificationsDropdown.style.display === 'block';
    notificationsDropdown.style.display = isVisible ? 'none' : 'block';
});

function cargarNotificaciones() {
    fetch('api/notificaciones/obtener.php')
        .then(res => res.json())
        .then(data => {
            notificationsList.innerHTML = '';

            if (!data || data.length === 0) {
                notificationsList.innerHTML = '<li class="empty">No hay notificaciones</li>';
                notificationCount.textContent = 0;
                notificationCount.style.display = 'none';
                return;
            }

            let noLeidas = 0;
            data.forEach(n => {
                const li = document.createElement('li');
                li.classList.add('notification-item');
                li.dataset.id = n.id_notificacion;
                if (n.leido == 0) {
                    li.classList.add('unread');
                    noLeidas++;
                }

                // Icono según tipo
                let iconClass = 'fa-solid fa-bell';
                switch (n.tipo) {
                    case 'pago': iconClass = 'fa-solid fa-money-bill'; break;
                    case 'reserva': iconClass = 'fa-solid fa-hotel'; break;
                    case 'soporte': iconClass = 'fa-solid fa-headset'; break;
                    case 'sistema': iconClass = 'fa-solid fa-cogs'; break;
                }

                li.innerHTML = `
                    <span class="notification-icon"><i class="${iconClass}"></i></span>
                    <span class="notification-text"><strong>${n.titulo}</strong> - ${n.mensaje}</span>
                    <button class="mark-read" aria-label="Marcar como leída">
                        <i class="fa-solid fa-check"></i>
                    </button>
                `;

                // Click en marcar como leída
                li.querySelector('.mark-read').addEventListener('click', (e) => {
                    e.stopPropagation();
                    marcarLeida(n.id_notificacion, li);
                });

                notificationsList.appendChild(li);
            });

            // Mostrar / ocultar contador
            if (noLeidas > 0) {
                notificationCount.textContent = noLeidas;
                notificationCount.style.display = 'inline-block';
            } else {
                notificationCount.style.display = 'none';
            }
        })
        .catch(err => console.error('Error al cargar notificaciones:', err));
}

function marcarLeida(id, li) {
    fetch('api/notificaciones/marcar_leida.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ id })
    })
    .then(res => res.json())
    .then(() => {
        li.classList.remove('unread');
        cargarNotificaciones();
    })
    .catch(err => console.error('Error al marcar notificación como leída:', err));
}

if (markAllBtn) {
    markAllBtn.addEventListener('click', () => {
        const ids = Array.from(notificationsList.querySelectorAll('.notification-item.unread'))
                         .map(li => li.dataset.id);

        if (ids.length === 0) return;

        fetch('api/notificaciones/marcar_todas_leidas.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ ids })
        })
        .then(res => res.json())
        .then(() => {
            cargarNotificaciones();
        })
        .catch(err => console.error('Error al marcar todas como leídas:', err));
    });
}

document.addEventListener('click', (e) => {
    if (
        !notificationsBtn.contains(e.target) &&
        !notificationsDropdown.contains(e.target)
    ) {
        notificationsDropdown.style.display = 'none';
    }
});

document.addEventListener('DOMContentLoaded', () => {
    cargarNotificaciones();
});

const pusher = new Pusher('33c3a03029c5c6bdab28', {
    cluster: 'us2',
    forceTLS: true
});

const channel = pusher.subscribe(`private-user-${USER_ID}`);
channel.bind('notificacion-nueva', function (data) {
    cargarNotificaciones();
});
