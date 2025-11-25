<?php 
if (session_status() === PHP_SESSION_NONE) session_start();
require_once(__DIR__ . '/../../controllers/tours/tours.controlador.php');

if (!isset($_SESSION['id_usuarios']) || ($_SESSION['id_perfiles'] ?? 0) != 14) {
    header('Location: index.php?page=login&message=Acceso no autorizado.&status=danger');
    exit;
}

$id_tour = (int)($_GET['id_tour'] ?? 0);
if (!$id_tour) {
    header('Location: index.php?page=mis_tours');
    exit;
}

$controlador = new ToursControlador();
$data = $controlador->obtenerDatosTour($id_tour);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Editar Tour</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="stylesheet" href="assets/css/mi_perfil_proveedor.css">
    <link rel="stylesheet" href="assets/css/hotel_carga.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>

<body>
<main class="contenido-principal">
    <div class="container">
        <div class="panel">
            <h2>Editar Tour</h2>
            <p class="hint">Modificá solo los campos que se pueden actualizar.</p>

            <form id="formTour" enctype="multipart/form-data" class="grid grid-2" method="POST">
                
                <input type="hidden" name="action" value="editar">
                <input type="hidden" name="id_tour" 
                    value="<?= htmlspecialchars($data['tourData']['id_tour'] ?? '') ?>">

                <div>
                    <label for="nombre_tour">Nombre del tour</label>
                    <input type="text" id="nombre_tour" name="nombre_tour"
                        value="<?= htmlspecialchars($data['tourData']['nombre_tour'] ?? '') ?>" required>
                </div>

                <div>
                    <label for="duracion_horas">Duración (HH:MM)</label>
                    <input type="text" id="duracion_horas" name="duracion_horas"
                        value="<?= htmlspecialchars(substr($data['tourData']['duracion_horas'] ?? '00:00',0,5)) ?>"
                        required>
                </div>

                <div>
                    <label for="precio_por_persona">Precio por persona</label>
                    <input type="number" id="precio_por_persona" name="precio_por_persona" min="0"
                        value="<?= htmlspecialchars($data['tourData']['precio_por_persona'] ?? '') ?>" required>
                </div>

                <div>
                    <label for="hora_encuentro">Hora de encuentro</label>
                    <input type="text" id="hora_encuentro" name="hora_encuentro"
                        value="<?= htmlspecialchars(substr($data['tourData']['hora_encuentro'] ?? '00:00',0,5)) ?>"
                        required>
                </div>

                <div style="grid-column: 1 / -1;">
                    <label for="lugar_encuentro">Punto de encuentro</label>
                    <input type="text" id="lugar_encuentro" name="lugar_encuentro"
                        value="<?= htmlspecialchars($data['tourData']['lugar_encuentro'] ?? '') ?>" required>
                </div>

                <div style="grid-column: 1 / -1;">
                    <label for="direccion">Dirección completa</label>
                    <input type="text" id="direccion" name="direccion"
                        value="<?= htmlspecialchars($data['tourData']['direccion'] ?? '') ?>" 
                        placeholder="Ej: Belgrano 836, Formosa" required>
                </div>

                <div style="grid-column: 1 / -1;">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion"><?= 
                        htmlspecialchars($data['tourData']['descripcion'] ?? '') ?></textarea>
                </div>

                <div>
                    <label for="imagen_principal">Imagen principal</label>

                    <?php if (!empty($data['tourData']['imagen_principal'])): ?>
                        <img src="assets/images/<?= $data['tourData']['imagen_principal'] ?>" 
                             style="width:150px; border-radius:6px;"> <br>
                    <?php endif; ?>

                    <input type="file" id="imagen_principal" name="imagen_principal" accept="image/*">
                </div>

                <div class="actions" style="grid-column: 1 / -1;">
                    <a href="index.php?page=mis_tours" class="btn secondary">Cancelar</a>
                    <button type="submit" class="btn">Guardar cambios</button>
                </div>

            </form>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
flatpickr("#duracion_horas", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true,
    defaultDate: "<?= substr($data['tourData']['duracion_horas'] ?? '00:00', 0, 5) ?>"
});

flatpickr("#hora_encuentro", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true,
    defaultDate: "<?= substr($data['tourData']['hora_encuentro'] ?? '00:00', 0, 5) ?>"
});
</script>

<script src="assets/js/tours_carga.js"></script>

</body>
</html>
