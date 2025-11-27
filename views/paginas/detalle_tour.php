<?php
require_once("models/tour.php");
require_once("models/stock_tour.php"); 

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: ID de tour no especificado.");
}

$id_tour = (int) $_GET['id'];
$tourModel = new Tour();
$tour = $tourModel->traer_tour($id_tour);

if (!$tour) {
    die("Error: Tour no encontrado.");
}

$tourStockModel = new Tour_Stock();
$fechas_disponibles = $tourStockModel->traer_fechas_disponibles($id_tour);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($tour['nombre_tour']); ?></title>
    <link rel="stylesheet" href="assets/css/detalle_tour.css">
</head>
<body>
    <div class="tour-detalle">
        <div class="imagen-principal">
            <img src="assets/images/<?= htmlspecialchars($tour['imagen_principal']); ?>" alt="Imagen del tour">
        </div>

        <div class="tour-contenedor">
            <div class="tour-info">
                <h1><?= htmlspecialchars($tour['nombre_tour']); ?></h1>
                <p><strong>Duración:</strong> <?= htmlspecialchars($tour['duracion_horas']); ?> horas</p>
                <p><strong>Lugar de encuentro:</strong> <?= htmlspecialchars($tour['lugar_encuentro']); ?></p>
                <p><strong>Hora de encuentro:</strong> <?= htmlspecialchars($tour['hora_encuentro']); ?></p>
                <p><strong>Precio por persona:</strong> $<?= number_format($tour['precio_por_persona'], 2, ',', '.'); ?></p>

                <div class="descripcion">
                    <h2>Descripción</h2>
                    <p><?= nl2br(htmlspecialchars($tour['descripcion'])); ?></p>
                </div>

                <div class="acciones">
                    <label for="fecha_tour"><strong>Elegir fecha:</strong></label>
                    <select name="fecha" id="fecha_tour">
                        <option value="">Seleccione una fecha</option>
                        <?php foreach ($fechas_disponibles as $f): ?>
                            <option value="<?= $f['fecha']; ?>">
                                <?= date("d/m/Y", strtotime($f['fecha'])); ?> 
                                (<?= $f['cupos_disponibles']; ?> disponibles)
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label for="cantidad_personas"><strong>¿Cuántas personas?</strong></label>
                    <input type="number" 
                           id="cantidad_personas" 
                           min="1" 
                           max="1" 
                           value="1">

                    <button type="button" class="btn-reserva"
                        onclick="agregarTourAlCarrito(
                            <?= $tour['id_tour'] ?>,
                            document.getElementById('fecha_tour').value,
                            document.getElementById('cantidad_personas').value,
                            <?= (float)$tour['precio_por_persona'] ?>
                        )">
                        Agregar al carrito
                    </button>
                </div>
            </div>

            <?php if (!empty($tour['direccion'])): ?>
            <div class="tour-mapa">
                <h2>Ubicación</h2>
                <iframe src="https://www.google.com/maps?q=<?= urlencode($tour['direccion']) ?>&output=embed"
                        width="100%" height="400" style="border:0;" allowfullscreen loading="lazy"></iframe>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include_once(__DIR__ . '/../componentes/pie.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const USER_ID = <?= (int)($_SESSION['id_usuarios'] ?? 0) ?>;
        window.fechasDisponibles = <?= json_encode($fechas_disponibles); ?>;
    </script>
    <script src="assets/js/carrito.js"></script> 
    <script src="assets/js/detalle_tour_carrito.js"></script> 
</body>
</html>