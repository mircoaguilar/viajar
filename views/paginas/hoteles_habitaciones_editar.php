<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once(__DIR__ . '/../../models/hotel.php');
require_once(__DIR__ . '/../../models/hotel_habitaciones.php');
require_once(__DIR__ . '/../../models/tipo_habitacion.php');

if (!isset($_SESSION['id_usuarios']) || !in_array($_SESSION['id_perfiles'] ?? 0, [2,3])) {
    header("Location: index.php?page=login&message=Acceso no autorizado&status=danger");
    exit;
}

$id_usuario = $_SESSION['id_usuarios'];
$id_habitacion = (int)($_GET['id_habitacion'] ?? 0);

if (!$id_habitacion) {
    header("Location: index.php?page=hoteles_mis_hoteles&message=Habitación no especificada&status=danger");
    exit;
}

$habitacionModel = new Hotel_Habitaciones();
$hotelModel = new Hotel();
$tipoModel = new TipoHabitacion();

$habitacion = $habitacionModel->traer_por_id($id_habitacion);

if (!$habitacion) {
    header("Location: index.php?page=hoteles_mis_hoteles&message=Habitación no encontrada&status=danger");
    exit;
}

$id_hotel = $habitacion["rela_hotel"];

if (!$hotelModel->verificar_propietario($id_hotel, $id_usuario)) {
    header("Location: index.php?page=proveedores_perfil&message=Acceso denegado");
    exit;
}

$tipos = $tipoModel->traer_tipos_habitaciones();

$fotos_existentes = [];
if (!empty($habitacion["fotos"])) {
    $fotos_existentes = is_array($habitacion["fotos"])
        ? $habitacion["fotos"]
        : (json_decode($habitacion["fotos"], true) ?: []);
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar habitación</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/mi_perfil_proveedor.css">
    <link rel="stylesheet" href="assets/css/hotel_carga.css">
</head>
<body>

<main class="contenido-principal">
    <div class="container">
        <div class="panel">

            <h2>Editar habitación</h2>
            <p class="hint">Modificá los datos de esta habitación.</p>

            <form id="formHabitacion" enctype="multipart/form-data" class="grid grid-2" method="POST"
                action="controllers/habitaciones/habitaciones.controlador.php">

                <input type="hidden" name="action" value="actualizar">
                <input type="hidden" name="id_hotel_habitacion" value="<?= $habitacion['id_hotel_habitacion'] ?>">
                <input type="hidden" name="rela_hotel" value="<?= $habitacion['rela_hotel'] ?>">

                <div>
                    <label>Tipo de Habitación</label>
                    <select name="rela_tipo_habitacion" required>
                        <option value="">Seleccionar tipo…</option>
                        <?php foreach ($tipos as $t): ?>
                            <option value="<?= $t['id_tipo_habitacion'] ?>"
                                <?= $t['id_tipo_habitacion'] == $habitacion['rela_tipo_habitacion'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($t['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label>Capacidad Máxima</label>
                    <input type="number" name="capacidad_maxima" min="1"
                        value="<?= htmlspecialchars($habitacion['capacidad_maxima']) ?>" required>
                </div>

                <div>
                    <label>Precio base por noche</label>
                    <input type="number" step="0.01" name="precio_base_noche"
                        value="<?= htmlspecialchars($habitacion['precio_base_noche']) ?>" required>
                </div>

                <div style="grid-column:1 / -1;">
                    <label>Descripción</label>
                    <textarea name="descripcion"><?= htmlspecialchars($habitacion["descripcion"] ?? "") ?></textarea>
                </div>

                <!-- FOTOS -->
                <div style="grid-column:1 / -1;">
                    <label>Fotos de la habitación</label>
                    <input type="file" name="fotos[]" accept="image/*" multiple>
                    <small>Puedes subir varias imágenes. Las fotos actuales se conservarán salvo que las elimines.</small>

                    <?php if (!empty($fotos_existentes)): ?>
                        <?php foreach ($fotos_existentes as $foto): ?>
                            <div style="display:flex; align-items:center; gap:10px; margin:5px 0;">
                                <img src="<?= $foto ?>" style="width:100px;"
                                    onerror="this.src='assets/images/sin-foto.jpg'">
                                <label>
                                    <input type="checkbox" name="borrar_fotos[]" value="<?= $foto ?>"> Borrar
                                </label>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No hay fotos cargadas.</p>
                    <?php endif; ?>
                </div>

                <div class="actions" style="grid-column:1 / -1;">
                    <a href="index.php?page=hoteles_habitaciones&id_hotel=<?= $id_hotel ?>" class="btn secondary">Cancelar</a>
                    <button type="submit" class="btn">Guardar cambios</button>
                </div>

            </form>

        </div>
    </div>
</main>

<script src="assets/js/hotel_carga.js"></script>

</body>
</html>
