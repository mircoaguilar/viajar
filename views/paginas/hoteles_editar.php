<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once(__DIR__ . '/../../controllers/hoteles/hoteles.controlador.php');

if (!isset($_SESSION['id_usuarios']) || !in_array($_SESSION['id_perfiles'] ?? 0, [2, 3])) { 
    header('Location: index.php?page=login&message=Acceso no autorizado.&status=danger'); 
    exit; 
}

$id_usuario = $_SESSION['id_usuarios'];
$id_hotel = (int)($_GET['id_hotel'] ?? 0);

if (!$id_hotel) {
    header('Location: index.php?page=proveedores_perfil'); 
    exit;
}

$controlador = new HotelesControlador();
$data = $controlador->obtenerDatosHotel($id_hotel);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Editar Hotel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="assets/css/mi_perfil_proveedor.css">
    <link rel="stylesheet" href="assets/css/hotel_carga.css">
</head>
<body>
    <main class="contenido-principal">
        <div class="container">
            <div class="panel">
                <h2>Editar Hotel</h2>
                <p class="hint">Modificá la información de tu hotel.</p>
                <form id="formHotel" enctype="multipart/form-data" class="grid grid-2" method="POST">
                    <input type="hidden" name="action" value="actualizar">
                    <input type="hidden" name="id_hotel" value="<?= htmlspecialchars($data['hotelData']['id_hotel'] ?? '') ?>">
                    <div>
                        <label>Nombre del Hotel</label>
                        <input type="text" name="hotel_nombre" value="<?= htmlspecialchars($data['hotelData']['hotel_nombre'] ?? '') ?>" required>
                    </div>
                    <div>
                        <label>Provincia</label>
                        <select name="rela_provincia" id="rela_provincia" required>
                            <option value="">Seleccionar provincia...</option>
                            <?php foreach ($data['provincias'] as $prov): ?>
                                <option value="<?= $prov['id_provincia'] ?>" <?= ($prov['id_provincia'] == ($data['hotelData']['rela_provincia'] ?? '')) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($prov['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label>Ciudad</label>
                        <select name="rela_ciudad" id="rela_ciudad" required>
                            <option value="">Seleccionar ciudad...</option>
                            <?php if ($data['hotelData']['rela_provincia']): 
                                $ciudades = $controlador->obtenerCiudadesPorProvincia($data['hotelData']['rela_provincia']);
                                foreach ($ciudades as $c): ?>
                                    <option value="<?= $c['id_ciudad'] ?>" <?= ($c['id_ciudad'] == ($data['hotelData']['rela_ciudad'] ?? '')) ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($c['nombre']) ?>
                                    </option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                    <div>
                        <label>Dirección</label>
                        <input type="text" name="direccion" value="<?= htmlspecialchars($data['hotelInfoData']['direccion'] ?? '') ?>">
                    </div>
                    <div>
                        <label>Imagen principal</label>
                        <?php if (!empty($data['hotelData']['imagen_principal'])): ?>
                            <img src="assets/images/<?= $data['hotelData']['imagen_principal'] ?>" style="width:150px;"> <br>
                        <?php endif; ?>
                        <input type="file" name="imagen_principal" accept="image/*">
                    </div>
                    <div style="grid-column: 1 / -1;">
                        <label>Descripción</label>
                        <textarea name="descripcion"><?= htmlspecialchars($data['hotelInfoData']['descripcion'] ?? '') ?></textarea>
                    </div>
                    <div style="grid-column: 1 / -1;">
                        <label>Servicios</label>
                        <textarea name="servicios"><?= htmlspecialchars($data['hotelInfoData']['servicios'] ?? '') ?></textarea>
                    </div>
                    <div>
                        <label>Políticas de cancelación</label>
                        <textarea name="politicas_cancelacion"><?= htmlspecialchars($data['hotelInfoData']['politicas_cancelacion'] ?? '') ?></textarea>
                    </div>
                    <div>
                        <label>Reglas</label>
                        <textarea name="reglas"><?= htmlspecialchars($data['hotelInfoData']['reglas'] ?? '') ?></textarea>
                    </div>
                    <div style="grid-column: 1 / -1;">
                        <label>Fotos adicionales</label>
                        <input type="file" name="fotos[]" accept="image/*" multiple>
                        <small>Puedes seleccionar varias imágenes a la vez</small>
                        <?php if (!empty($data['hotelInfoData']['fotos'])): 
                            $fotosArray = json_decode($data['hotelInfoData']['fotos'], true); 
                            foreach ($fotosArray as $foto): ?>
                                <div style="display:flex; align-items:center; gap:10px; margin:5px 0;">
                                    <img src="assets/images/<?= $foto ?>" style="width:100px;">
                                    <label>
                                        <input type="checkbox" name="borrar_fotos[]" value="<?= $foto ?>"> Borrar
                                    </label>
                                </div>
                        <?php endforeach; endif; ?>
                    </div>
                    <div class="actions" style="grid-column: 1 / -1;">
                        <a href="index.php?page=hoteles_mis_hoteles" class="btn secondary">Cancelar</a>
                        <button type="submit" class="btn">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <script src="assets/js/hotel_carga.js"></script>
</body>
</html>
