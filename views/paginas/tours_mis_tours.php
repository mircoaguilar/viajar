<?php
require_once('models/Tour.php');
require_once('models/proveedor.php');
require_once('controllers/proveedores/proveedores.controlador.php');

if (!isset($_SESSION['id_usuarios']) || !in_array($_SESSION['id_perfiles'], [13,14])) { 
    header('Location: index.php?page=login&message=Acceso no autorizado.&status=danger');
    exit;
}

$id_usuario = $_SESSION['id_usuarios'];
$controlador = new ProveedoresControlador();
$tours = $controlador->mis_tours($id_usuario); 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Tours</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/hoteles_mis_hoteles.css">
</head>
<body>

<main class="contenido-principal">
    <div class="container">
        <h2>Mis Tours Guiados</h2>
        <p class="hint">Acá podés ver tus tours registrados, su estado y administrar sus datos.</p>

        <div style="margin: 10px 0;">
            <a href="controllers/exportar.controlador.php?tipo=tours" class="btn secondary">Exportar a Excel</a>
            <a href="controllers/exportar_pdf.controlador.php?tipo=tour" class="btn secondary">Exportar a PDF</a>
        </div>

        <?php if (!empty($tours)): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Duración</th>
                    <th>Precio</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tours as $t): ?>
                    <tr>
                        <td>
                            <?php if (!empty($t['imagen_principal'])): ?>
                                <img src="assets/images/<?= htmlspecialchars($t['imagen_principal']) ?>" alt="Imagen <?= htmlspecialchars($t['nombre_tour']) ?>" style="width:100px; height:auto;">
                            <?php else: ?>
                                <span>Sin imagen</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($t['nombre_tour']) ?></td>
                        <td><?= htmlspecialchars($t['duracion_horas']) ?></td>
                        <td>$<?= number_format($t['precio_por_persona'], 2) ?></td>
                        <td>
                            <?php
                                $estado = strtolower($t['estado_revision'] ?? 'pendiente');
                                $clase_estado = 'estado-' . $estado;
                                echo "<span class='estado $clase_estado'>$estado</span>";
                            ?>
                        </td>
                        <td>
                            <a href="index.php?page=tours_editar&id_tour=<?= $t['id_tour'] ?>" class="btn">Editar</a>

                            <?php if ($estado === 'aprobado'): ?>
                                <a href="index.php?page=tours_stock&id_tour=<?= $t['id_tour'] ?>" class="btn secondary">Gestionar Stock</a>
                            <?php else: ?>
                                <a class="btn secondary disabled" title="Disponible solo cuando el tour esté aprobado">Gestionar Stock</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p>No tenés tours registrados aún.</p>
        <?php endif; ?>

        <div style="margin-top:20px;">
            <a href="index.php?page=tours_carga" class="btn">Agregar Nuevo Tour</a>
        </div>
    </div>
</main>

</body>
</html>
