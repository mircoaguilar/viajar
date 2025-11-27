<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once(__DIR__ . '/../../controllers/transportes/transporte.controlador.php');

if (!isset($_SESSION['id_usuarios']) || ($_SESSION['id_perfiles'] ?? 0) != 5) {
    header('Location: index.php?page=login&message=Acceso no autorizado.&status=danger');
    exit;
}

$id_transporte = (int)($_GET['id_transporte'] ?? 0);
if (!$id_transporte) {
    header('Location: index.php?page=mis_transportes');
    exit;
}

$controlador = new TransportesControlador();
$data = $controlador->obtenerDatosTransporte($id_transporte);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Editar Transporte</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="assets/css/mi_perfil_proveedor.css">
    <link rel="stylesheet" href="assets/css/hotel_carga.css">
</head>
<body>
<main class="contenido-principal">
    <div class="container">
        <div class="panel">
            <h2>Editar Transporte</h2>
            <p class="hint">Modificá solo los campos que se pueden actualizar.</p>

            <form id="formTransporte" enctype="multipart/form-data" class="grid grid-2" method="POST">
                <input type="hidden" name="action" value="editar">
                <input type="hidden" name="id_transporte" value="<?= htmlspecialchars($data['transporteData']['id_transporte'] ?? '') ?>">

                <div>
                    <label for="transporte_matricula">Matrícula / Patente</label>
                    <input type="text" id="transporte_matricula" name="transporte_matricula" 
                        value="<?= htmlspecialchars($data['transporteData']['transporte_matricula'] ?? '') ?>" required>
                </div>

                <div>
                    <label for="rela_tipo_transporte">Tipo de transporte</label>
                    <select id="rela_tipo_transporte" name="rela_tipo_transporte" required>
                        <option value="">Seleccionar...</option>
                        <?php foreach ($data['tipos'] as $tipo): ?>
                            <option value="<?= $tipo['id_tipo_transporte'] ?>" 
                                <?= ($tipo['id_tipo_transporte'] == $data['transporteData']['rela_tipo_transporte']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($tipo['descripcion']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="nombre_servicio">Nombre del transporte</label>
                    <input type="text" id="nombre_servicio" name="nombre_servicio"
                        value="<?= htmlspecialchars($data['transporteData']['nombre_servicio'] ?? '') ?>" required>
                </div>

                <div style="grid-column: 1 / -1;">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion"><?= htmlspecialchars($data['transporteData']['descripcion'] ?? '') ?></textarea>
                </div>

                <div>
                    <label for="imagen_principal">Imagen principal</label>
                    <?php if (!empty($data['transporteData']['imagen_principal'])): ?>
                        <img src="assets/images/<?= $data['transporteData']['imagen_principal'] ?>" style="width:150px;"> <br>
                    <?php endif; ?>
                    <input type="file" id="imagen_principal" name="imagen_principal" accept="image/*">
                </div>

                <div style="grid-column: 1 / -1;">
                    <h3>Pisos (solo lectura)</h3>
                    <?php if (!empty($data['pisosData'])): ?>
                        <?php foreach ($data['pisosData'] as $piso): ?>
                            <div style="border:1px solid #ccc; padding:10px; margin-bottom:5px;">
                                <strong>Piso <?= $piso['numero_piso'] ?></strong><br>
                                Filas: <?= $piso['filas'] ?>, Asientos por fila: <?= $piso['asientos_por_fila'] ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No hay pisos configurados.</p>
                    <?php endif; ?>
                </div>

                <div class="actions" style="grid-column: 1 / -1;">
                    <a href="index.php?page=mis_transportes" class="btn secondary">Cancelar</a>
                    <button type="submit" class="btn">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</main>

<script src="assets/js/transportes_carga.js"></script>
</body>
</html>
