<?php
require_once('models/transporte.php');

if (!isset($_GET['id'])) {
    die("Transporte no especificado.");
}

$transporteModel = new Transporte();
$transporte = $transporteModel->traer_transporte($_GET['id']);
if (!$transporte) {
    die("Transporte no encontrado.");
}

$asientos_ocupados_piso1 = [1, 5, 6]; // puedes traerlo de la DB
$asientos_ocupados_piso2 = [2, 4, 7]; // según capacidad
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Detalle del viaje - <?php echo htmlspecialchars($transporte['nombre_servicio']); ?></title>
  <link rel="stylesheet" href="assets/css/detalle_transporte.css">
</head>
<body>
  <h2><?php echo htmlspecialchars($transporte['nombre_servicio']); ?></h2>
  <p><strong>Descripción:</strong> <?php echo htmlspecialchars($transporte['descripcion']); ?></p>
  <p><strong>Tipo:</strong> <?php echo htmlspecialchars($transporte['tipo_transporte']); ?></p>
  <p><strong>Proveedor:</strong> <?php echo htmlspecialchars($transporte['proveedor_nombre']); ?></p>
  <p><strong>Capacidad:</strong> <?php echo htmlspecialchars($transporte['transporte_capacidad']); ?> pasajeros</p>
  <img src="assets/images/<?php echo htmlspecialchars($transporte['imagen_principal']); ?>" alt="Imagen del transporte" width="400">

  <h3>Seleccioná tus asientos</h3>
  <div class="bus-wrapper">
    <div class="bus-container">
      <h3>Piso 1</h3>
      <div id="asientos-piso1" class="asientos"></div>
    </div>
    <div class="bus-container">
      <h3>Piso 2</h3>
      <div id="asientos-piso2" class="asientos"></div>
    </div>
  </div>

  <div class="info">
    <p>🟩 Disponible &nbsp; 🟦 Seleccionado &nbsp; 🟥 Ocupado</p>
    <div id="resumen">
      <p><strong>Seleccionados:</strong> <span id="contador">0</span></p>
      <p><strong>Total:</strong> $<span id="total">0</span></p>
    </div>
    <button id="btn-confirmar">Agregar al carrito</button>
  </div>

  <script>
    const ocupadosPiso1 = <?php echo json_encode($asientos_ocupados_piso1); ?>;
    const ocupadosPiso2 = <?php echo json_encode($asientos_ocupados_piso2); ?>;
  </script>
  <script src="assets/js/asientos.js"></script>
</body>
</html>
