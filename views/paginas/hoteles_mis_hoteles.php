<?php
require_once('models/proveedor.php');
require_once('models/hotel.php');
require_once('controllers/proveedores/proveedores.controlador.php');

if (!isset($_SESSION['id_usuarios']) || ($_SESSION['id_perfiles'] ?? 0) != 3) {
    header('Location: index.php?page=login&message=Acceso no autorizado.&status=danger');
    exit;
}

$id_usuario = $_SESSION['id_usuarios'];
$controlador = new ProveedoresControlador();
$hoteles = $controlador->mis_hoteles($id_usuario);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Hoteles</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/hoteles_mis_hoteles.css">
</head>
<body>

<main class="contenido-principal">
    <div class="container">
        <h2>Mis Hoteles</h2>
        <p class="hint">Acá podés ver tus hoteles registrados, su estado y administrar sus datos.</p>

        <div style="margin: 10px 0;">
            <a href="controllers/exportar.controlador.php?tipo=hoteles" class="btn secondary">Exportar a Excel</a>
            <a href="controllers/exportar_pdf.controlador.php?tipo=hotel" class="btn secondary">Exportar a PDF</a>
        </div>

        <?php if (!empty($hoteles)): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Provincia</th>
                    <th>Ciudad</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($hoteles as $h): ?>
                    <tr>
                        <td>
                            <?php if (!empty($h['imagen_principal'])): ?>
                                <img src="assets/images/<?= htmlspecialchars($h['imagen_principal']) ?>" alt="Imagen <?= htmlspecialchars($h['hotel_nombre']) ?>" style="width:100px; height:auto;">
                            <?php else: ?>
                                <span>Sin imagen</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($h['hotel_nombre']) ?></td>
                        <td><?= htmlspecialchars($h['provincia_nombre']) ?></td>
                        <td><?= htmlspecialchars($h['ciudad_nombre']) ?></td>
                        <td><?= htmlspecialchars($h['descripcion'] ?? '-') ?></td>
                        <td>
                            <?php
                                $estado = strtolower($h['estado_revision'] ?? 'pendiente');
                                $clase_estado = 'estado-' . $estado;
                                echo "<span class='estado $clase_estado'>$estado</span>";
                            ?>
                        </td>

                        <td>
                            <a href="index.php?page=hoteles_editar&id_hotel=<?= $h['id_hotel'] ?>" class="btn">Editar</a>

                            <?php if ($estado === 'aprobado'): ?>
                                <a href="index.php?page=hoteles_habitaciones&id_hotel=<?= $h['id_hotel'] ?>" class="btn secondary">Ver Habitaciones</a>
                            <?php else: ?>
                                <a class="btn secondary disabled" title="Disponible solo cuando el hotel esté aprobado">Ver Habitaciones</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p>No tenés hoteles registrados aún.</p>
        <?php endif; ?>

        <div style="margin-top:20px;">
            <a href="index.php?page=hoteles_carga" class="btn">Agregar Nuevo Hotel</a>
        </div>
    </div>
</main>

</body>
</html>
