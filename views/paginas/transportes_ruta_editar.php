<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once('models/ciudad.php');
require_once(__DIR__ . '/../../controllers/transportes/rutas.controlador.php');

if (!isset($_SESSION['id_usuarios']) || ($_SESSION['id_perfiles'] ?? 0) != 5) {
    header('Location: index.php?page=login&message=Acceso no autorizado.&status=danger');
    exit;
}

$id_ruta = (int)($_GET['id_ruta'] ?? 0);
if (!$id_ruta) {
    header('Location: index.php?page=mis_transportes');
    exit;
}

$ciudadModel = new Ciudad();
$ciudades = $ciudadModel->traer_ciudades();

$controlador = new RutasControlador();
$data = $controlador->obtenerDatosRuta($id_ruta);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Editar Ruta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="assets/css/mi_perfil_proveedor.css">
    <link rel="stylesheet" href="assets/css/hotel_carga.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body>

<main class="contenido-principal">
    <div class="container">
        <div class="panel">

            <h2>Editar Ruta</h2>
            <p class="hint">Modificá los datos correspondientes de la ruta.</p>

            <form id="formRutaEditar" class="grid grid-2" method="POST">

                <input type="hidden" name="action" value="actualizar">
                <input type="hidden" name="id_ruta" value="<?= htmlspecialchars($data['ruta']['id_ruta']) ?>">

                <div>
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre"
                        value="<?= htmlspecialchars($data['ruta']['nombre']) ?>" required>
                </div>

                <div>
                    <label for="trayecto">Trayecto</label>
                    <input type="text" id="trayecto" name="trayecto"
                        value="<?= htmlspecialchars($data['ruta']['trayecto']) ?>" required>
                </div>

                <div>
                    <label for="rela_transporte">Transporte</label>
                    <select id="rela_transporte" name="rela_transporte" required>
                        <option value="">Seleccionar...</option>
                        <?php foreach ($data['transportes'] as $t): ?>
                            <option value="<?= $t['id_transporte'] ?>"
                                <?= ($t['id_transporte'] == $data['ruta']['rela_transporte']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($t['nombre_servicio']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="rela_ciudad_origen">Ciudad Origen</label>
                    <select id="rela_ciudad_origen" name="rela_ciudad_origen" required>
                        <option value="">Seleccionar origen...</option>
                        <?php foreach ($ciudades as $c): ?>
                            <option value="<?= $c['id_ciudad'] ?>"
                                <?= ($c['id_ciudad'] == $data['ruta']['rela_ciudad_origen']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="rela_ciudad_destino">Ciudad Destino</label>
                   <select id="rela_ciudad_destino" name="rela_ciudad_destino" required>
                        <option value="">Seleccionar destino...</option>
                        <?php foreach ($ciudades as $c): ?>
                            <option value="<?= $c['id_ciudad'] ?>"
                                <?= ($c['id_ciudad'] == $data['ruta']['rela_ciudad_destino']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="duracion">Duración (HH:MM)</label>
                    <input type="text" id="duracion" name="duracion"
                        value="<?= htmlspecialchars($data['ruta']['duracion']) ?>" required>
                </div>

                <div>
                    <label for="precio_por_persona">Precio por persona</label>
                    <input type="number" step="0.01" id="precio_por_persona" name="precio_por_persona"
                        value="<?= htmlspecialchars($data['ruta']['precio_por_persona']) ?>" required>
                </div>

                <div style="grid-column: 1 / -1;">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion"><?= htmlspecialchars($data['ruta']['descripcion']) ?></textarea>
                </div>

                <div class="actions" style="grid-column: 1 / -1;">
                    <a href="index.php?page=transportes_rutas&id_transporte=<?= $data['ruta']['rela_transporte'] ?>" 
                       class="btn secondary">Cancelar</a>

                    <button type="submit" class="btn">Guardar cambios</button>
                </div>

            </form>

        </div>
    </div>
</main>

<script src="assets/js/rutas_editar.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
flatpickr("#duracion", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true,
    defaultHour: 2,
    defaultMinute: 0
});
</script>
</body>
</html>
