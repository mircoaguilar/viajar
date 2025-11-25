<?php
require_once('models/proveedor.php');
require_once('models/transporte.php');
require_once('models/transporte_rutas.php');
require_once('controllers/proveedores/proveedores.controlador.php');

if (!isset($_SESSION['id_usuarios']) || ($_SESSION['id_perfiles'] ?? 0) != 5) {
    header('Location: index.php?page=login&message=Acceso no autorizado.&status=danger');
    exit;
}

$id_usuario = $_SESSION['id_usuarios'];
$id_transporte = (int)($_GET['id_transporte'] ?? 0);

if (!$id_transporte) {
    header('Location: index.php?page=mis_transportes');
    exit;
}

$controlador = new ProveedoresControlador();

if (!$controlador->verificar_propietario_transporte($id_transporte, $id_usuario)) {
    header('Location: index.php?page=transportes_mis_transportes&message=Acceso denegado.&status=danger');
    exit;
}

$rutaModel = new Transporte_Rutas();
$transporteModel = new Transporte();

$transporte = $transporteModel->traer_transporte($id_transporte)[0] ?? null;
$rutas = $rutaModel->traer_rutas_por_transporte($id_transporte);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Rutas del Transporte: <?= htmlspecialchars($transporte['nombre_servicio'] ?? '') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="assets/css/hoteles_mis_hoteles.css">
    
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border-bottom: 1px solid #ddd; }
        .status-active { color: green; font-weight: bold; }
        .status-inactive { color: red; font-weight: bold; }
        .actions a { margin-right: 5px; display: inline-block; }
    </style>
</head>

<body>
<main class="contenido-principal">
    <div class="container">
        
        <h2>Rutas de <?= htmlspecialchars($transporte['nombre_servicio'] ?? '') ?></h2>
        <p class="hint">Acá podés administrar las rutas de este vehículo y agregar nuevas rutas disponibles.</p>

        <a href="index.php?page=transportes_rutas_carga&id_transporte=<?= $id_transporte ?>" class="btn">Agregar nueva ruta</a>

        <br><br>

        <?php if (!empty($rutas)): ?>
       <table class="table">
            <thead>
                <tr>
                    <th>Origen</th>
                    <th>Destino</th>
                    <th>Trayecto</th>
                    <th>Duración</th>
                    <th>Precio por persona</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
            <?php foreach ($rutas as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['ciudad_origen']) ?></td>
                    <td><?= htmlspecialchars($r['ciudad_destino']) ?></td>
                    <td><?= htmlspecialchars($r['trayecto']) ?></td>
                    <td><?= htmlspecialchars($r['duracion']) ?></td>
                    <td>$<?= number_format($r['precio_por_persona'], 2) ?></td>

                    <?php 
                        $statusClass = ($r['activo'] == 1) ? 'status-active' : 'status-inactive';
                    ?>
                    <td class="<?= $statusClass ?>">
                        <?= ($r['activo'] == 1) ? 'Activa' : 'Inactiva' ?>
                    </td>

                    <td class="actions">

                        <a href="index.php?page=transportes_ruta_editar&id_ruta=<?= $r['id_ruta'] ?>" 
                        class="btn">Editar</a>

                        <a href="controllers/transportes/rutas.controlador.php?action=toggle&id_ruta=<?= $r['id_ruta'] ?>" 
                            class="btn">
                                <?= ($r['activo'] == 1) ? 'Desactivar' : 'Activar' ?>
                        </a>

                        <a href="index.php?page=transportes_viajes_carga&id_ruta=<?= $r['id_ruta'] ?>" 
                        class="btn secondary">
                            Nuevo viaje
                        </a>

                       <?php if (!empty($r['total_viajes']) && (int)$r['total_viajes'] > 0): ?>
                            <a href="index.php?page=transportes_viajes&id_ruta=<?= $r['id_ruta'] ?>" 
                            class="btn secondary">Ver viajes</a>
                        <?php else: ?>
                            <a class="btn secondary disabled" title="No hay viajes">Ver viajes</a>
                        <?php endif; ?>

                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <?php else: ?>
            <p>No hay rutas cargadas para este transporte.</p>
        <?php endif; ?>

    </div>
</main>

</body>
</html>
