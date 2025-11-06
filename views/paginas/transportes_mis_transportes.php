<?php
require_once('models/proveedor.php');
require_once('models/transporte.php');
require_once('controllers/proveedores/proveedores.controlador.php');

if (!isset($_SESSION['id_usuarios']) || ($_SESSION['id_perfiles'] ?? 0) != 5) {
    header('Location: index.php?page=login&message=Acceso no autorizado.&status=danger');
    exit;
}

$id_usuario = $_SESSION['id_usuarios'];
$controlador = new ProveedoresControlador();
$transportes = $controlador->mis_transportes($id_usuario);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Transportes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/hoteles_mis_hoteles.css"> 
</head>
<body>

<main class="contenido-principal">
    <div class="container">
        <h2>Mis Transportes</h2>
        <p class="hint">Acá podés ver tus vehículos registrados, su estado y administrar sus datos.</p>

        <div style="margin: 10px 0;">
            <a href="controllers/exportar.controlador.php?tipo=transportes" class="btn secondary">Exportar a Excel</a>
            <a href="controllers/exportar_pdf.controlador.php?tipo=transporte" class="btn secondary">Exportar a PDF</a>
        </div>

        <?php if (!empty($transportes)): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Nombre / Modelo</th>
                    <th>Tipo</th>
                    <th>Capacidad</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transportes as $t): ?>
                    <tr>
                        <td>
                            <?php if (!empty($t['imagen_principal'])): ?>
                                <img src="assets/images/<?= htmlspecialchars($t['imagen_principal']) ?>" 
                                     alt="<?= htmlspecialchars($t['nombre_servicio']) ?>" 
                                     style="width:100px; height:auto;">
                            <?php else: ?>
                                <span>Sin imagen</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($t['nombre_servicio']) ?></td>
                        <td><?= htmlspecialchars($t['tipo_transporte']) ?></td>
                        <td><?= htmlspecialchars($t['transporte_capacidad']) ?></td>
                        <td><?= htmlspecialchars($t['descripcion'] ?? '-') ?></td>

                        <td>
                            <?php
                                $estado = strtolower($t['estado_revision'] ?? 'pendiente');
                                $clase_estado = 'estado-' . $estado;
                                echo "<span class='estado $clase_estado'>$estado</span>";
                            ?>
                        </td>

                        <td>
                            <a href="index.php?page=transportes_editar&id_transporte=<?= $t['id_transporte'] ?>" class="btn">Editar</a>

                            <?php if ($estado === 'aprobado'): ?>
                                <a href="index.php?page=transportes_pisos&id_transporte=<?= $t['id_transporte'] ?>" class="btn secondary">Ver Pisos</a>
                                <a href="index.php?page=mis_rutas&id_transporte=<?= $t['id_transporte'] ?>" class="btn secondary">Ver Rutas</a>
                            <?php else: ?>
                                <a class="btn secondary disabled" title="Disponible cuando el transporte esté aprobado">Ver Pisos</a>
                                <a class="btn secondary disabled" title="Disponible cuando el transporte esté aprobado">Ver Rutas</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p>No tenés transportes registrados aún.</p>
        <?php endif; ?>

        <div style="margin-top:20px;">
            <a href="index.php?page=transportes_carga" class="btn">Agregar Nuevo Transporte</a>
        </div>
    </div>
</main>

</body>
</html>
