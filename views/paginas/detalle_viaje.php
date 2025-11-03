<?php
require_once('models/transporte.php');
require_once('models/transporte_piso.php');
require_once('models/transporte_rutas.php');
require_once('models/viaje.php');

if (!isset($_GET['id'])) {
    die("Transporte no especificado.");
}

$idTransporte = intval($_GET['id']);

$transporteModel = new Transporte();
$transporte = $transporteModel->traer_transporte($idTransporte);
if (!$transporte) die("Transporte no encontrado.");

$transportePisoModel = new TransportePiso();
$pisos = $transportePisoModel->traer_pisos_por_transporte($idTransporte);

$viajesModel = new Viaje();
$primerViaje = $viajesModel->traer_primer_viaje_por_transporte($idTransporte);
if (!$primerViaje) die("No hay viajes para este transporte.");

$idViaje = $primerViaje['id_viajes'];
$viaje = $viajesModel->traer_viaje_por_id($idViaje);

$rutaModel = new Transporte_Rutas();
$ruta = $rutaModel->traer_por_id($viaje['rela_transporte_rutas']);
if (!$ruta) die("Ruta no encontrada.");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Detalle del transporte - <?= htmlspecialchars($transporte['nombre_servicio']) ?></title>
  <link rel="stylesheet" href="assets/css/detalle_transporte.css">
</head>
<body 
  data-transporte-id="<?= $idTransporte ?>"
  data-viaje-id="<?= $idViaje ?>"
  data-precio="<?= $ruta['precio_por_persona'] ?>"
  data-fecha="<?= $viaje['viaje_fecha'] ?>"
  data-origen="<?= htmlspecialchars($ruta['rela_ciudad_origen']) ?>"
  data-destino="<?= htmlspecialchars($ruta['rela_ciudad_destino']) ?>"
  data-hora-salida="<?= $viaje['hora_salida'] ?>"
  data-hora-llegada="<?= $viaje['hora_llegada'] ?>"
>
  <h2><?= htmlspecialchars($transporte['nombre_servicio']) ?></h2>

  <div class="detalle-transporte">
    <img src="assets/images/<?= htmlspecialchars($transporte['imagen_principal']) ?>" alt="Imagen del transporte" width="400">
    <div class="info-transporte">
      <p><strong>Descripción:</strong> <?= htmlspecialchars($transporte['descripcion']) ?></p>
      <p><strong>Tipo:</strong> <?= htmlspecialchars($transporte['tipo_transporte']) ?></p>
      <p><strong>Proveedor:</strong> <?= htmlspecialchars($transporte['proveedor_nombre']) ?></p>
      <p><strong>Capacidad:</strong> <?= htmlspecialchars($transporte['transporte_capacidad']) ?> pasajeros</p>
      <p><strong>Ruta:</strong> <?= htmlspecialchars($ruta['trayecto']) ?> (<?= htmlspecialchars($ruta['ciudad_origen']) ?> → <?= htmlspecialchars($ruta['ciudad_destino']) ?>)</p>
      <p><strong>Fecha:</strong> <?= $viaje['viaje_fecha'] ?></p>
      <p><strong>Salida:</strong> <?= $viaje['hora_salida'] ?> | <strong>Llegada:</strong> <?= $viaje['hora_llegada'] ?></p>
      <p><strong>Precio por asiento:</strong> $<?= number_format($ruta['precio_por_persona'], 0, ',', '.') ?></p>
    </div>
  </div>

  <h3>Seleccioná tus asientos</h3>
  <div class="bus-wrapper">
    <?php foreach($pisos as $piso): ?>
      <div class="bus-container" 
           data-filas="<?= $piso['filas'] ?>" 
           data-asientos="<?= $piso['asientos_por_fila'] ?>" 
           data-numero="<?= $piso['numero_piso'] ?>">
        <h3>Piso <?= $piso['numero_piso'] ?></h3>
        <div id="asientos-piso<?= $piso['numero_piso'] ?>" class="asientos"></div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="info">
    <p>🟩 Disponible &nbsp; 🟦 Seleccionado &nbsp; 🟥 Ocupado</p>

    <div id="resumen">
      <p><strong>Seleccionados:</strong> <span id="contador">0</span></p>
      <p><strong>Total:</strong> $<span id="total">0</span></p>
    </div>

    <button id="btn-confirmar">Agregar al carrito</button>
  </div>

  <div id="modal-carrito" class="modal">
    <div class="modal-contenido">
      <span id="modal-cerrar" class="cerrar">&times;</span>
      <h3>Asientos seleccionados</h3>
      <div id="modal-lista"></div>
      <p><strong>Total:</strong> $<span id="modal-total">0</span></p>
      <button id="btn-agregar-carrito">Confirmar</button>
      <button id="btn-cancelar">Cancelar</button>
    </div>
  </div>

  <script src="assets/js/asientos.js"></script>
</body>
</html>
