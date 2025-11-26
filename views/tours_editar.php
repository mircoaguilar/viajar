<?php
require_once('models/Tour.php');

if (!isset($_SESSION['id_usuarios']) || !in_array($_SESSION['id_perfiles'], [13,14])) {
    header('Location: index.php?page=login&message=Acceso no autorizado.&status=danger');
    exit;
}

$id_tour = $_GET['id_tour'] ?? null;

if (!$id_tour) {
    header('Location: index.php?page=tours_mis_tours&message=ID de tour inválido&status=danger');
    exit;
}

$tourModel = new Tour();
$tour = $tourModel->traer_tour($id_tour);

if (!$tour) {
    header("Location: index.php?page=tours_mis_tours&message=El tour no existe&status=danger");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Tour</title>
    <link rel="stylesheet" href="assets/css/hoteles_carga.css">
</head>
<body>

<main class="contenido-principal">
    <div class="container">

        <h2>Editar Tour: <?= htmlspecialchars($tour['nombre_tour']) ?></h2>
        <p class="hint">Modificá los datos necesarios. Al guardar, el tour pasará nuevamente a revisión.</p>

        <form action="controllers/tours/tours.controlador.php" method="POST" enctype="multipart/form-data" class="formulario">
            
            <input type="hidden" name="action" value="editar">
            <input type="hidden" name="id_tour" value="<?= $tour['id_tour'] ?>">

            <!-- NOMBRE -->
            <div class="form-grupo">
                <label>Nombre del tour *</label>
                <input type="text" name="nombre_tour" value="<?= htmlspecialchars($tour['nombre_tour']) ?>" required>
            </div>

            <!-- DESCRIPCIÓN -->
            <div class="form-grupo">
                <label>Descripción *</label>
                <textarea name="descripcion" required><?= htmlspecialchars($tour['descripcion']) ?></textarea>
            </div>

            <!-- DURACIÓN -->
            <div class="form-grupo">
                <label>Duración (en horas) *</label>
                <input type="number" name="duracion_horas" min="1" value="<?= htmlspecialchars($tour['duracion_horas']) ?>" required>
            </div>

            <!-- PRECIO -->
            <div class="form-grupo">
                <label>Precio por persona *</label>
                <input type="number" step="0.01" min="0" name="precio_por_persona" 
                    value="<?= htmlspecialchars($tour['precio_por_persona']) ?>" required>
            </div>

            <!-- IMAGEN PRINCIPAL -->
            <div class="form-grupo">
                <label>Imagen principal</label>
                <?php if (!empty($tour['imagen_principal'])): ?>
                    <p>Imagen actual:</p>
                    <img src="assets/images/<?= $tour['imagen_principal'] ?>" style="width:150px; border-radius:5px; margin-bottom:10px;">
                <?php endif; ?>

                <input type="file" name="imagen_principal">
                <small>Si cargás una nueva imagen, reemplazará a la actual.</small>
            </div>

            <!-- BOTONES -->
            <div class="form-botones">
                <button type="submit" class="btn">Guardar Cambios</button>
                <a href="index.php?page=tours_mis_tours" class="btn secondary">Volver</a>
            </div>

        </form>

    </div>
</main>

</body>
</html>
