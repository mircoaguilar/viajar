<?php
require_once('models/transporte.php');
require_once('models/transporte_rutas.php'); 
require_once('models/viaje.php');
require_once('controllers/proveedores/proveedores.controlador.php');

$id_usuario = $_SESSION['id_usuarios'];
$id_ruta = (int)($_GET['id_ruta'] ?? 0);

if (!$id_ruta) {
    header('Location: index.php?page=mis_transportes');
    exit;
}

$controlador = new ProveedoresControlador();

if (!$controlador->verificar_propietario_ruta($id_ruta, $id_usuario)) {
    header('Location: index.php?page=mis_transportes&message=Acceso denegado.&status=danger');
    exit;
}

$rutaModel = new Transporte_Rutas();    
$viajeModel = new Viaje();             
$transporteModel = new Transporte();   


$ruta = $rutaModel->traer_por_id($id_ruta); 

$viajes = $viajeModel->traer_viajes_por_ruta($id_ruta);

if (!$ruta) {
    header('Location: index.php?page=mis_transportes&message=Ruta no encontrada.&status=danger');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Viajes de la Ruta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/hoteles_mis_hoteles.css">
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border-bottom: 1px solid #ddd; }
        .status-active { color: green; font-weight: bold; }
        .status-inactive { color: red; font-weight: bold; }
        .actions a { margin-right: 5px; }
    </style>
</head>

<body>
<main class="contenido-principal">
    <div class="container">
        <h2>Viajes de la ruta: <?= htmlspecialchars($ruta['nombre'] ?? '') ?></h2>
        <p class="hint">Listado de viajes programados para esta ruta.</p>


        <?php if (!empty($viajes)): ?>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Salida</th>
                    <th>Llegada</th>
                    <th>Asientos totales</th>
                    <th>Asientos disponibles</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
            <?php foreach ($viajes as $v): ?>
                <tr>
                    <td><?= htmlspecialchars($v['viaje_fecha']) ?></td>
                    <td><?= htmlspecialchars($v['hora_salida']) ?></td>
                    <td><?= htmlspecialchars($v['hora_llegada']) ?></td>
                    <td><?= htmlspecialchars($v['asientos_totales']) ?></td>
                    <td><?= htmlspecialchars($v['asientos_disponibles']) ?></td>

                    <?php 
                        $statusClass = ($v['activo'] == 1) ? 'status-active' : 'status-inactive';
                    ?>
                    <td class="<?= $statusClass ?>">
                        <?= ($v['activo'] == 1) ? 'Activo' : 'Inactivo' ?>
                    </td>

                    <td class="actions">
                        <a href="controllers/transportes/viajes.controlador.php?action=toggle&id_viaje=<?= $v['id_viajes'] ?>" class="btn">
                            <?= ($v['activo'] == 1) ? 'Desactivar' : 'Activar' ?>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <?php else: ?>
            <p>No hay viajes programados para esta ruta.</p>
        <?php endif; ?>
    </div>
</main>
</body>
</html>
