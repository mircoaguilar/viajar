<?php
if (!isset($_SESSION['id_usuarios']) || !in_array($_SESSION['id_perfiles'] ?? 0, [2,3])) {
    header('Location: index.php?page=login&message=Acceso no autorizado.&status=danger');
    exit;
}

require_once('models/hotel.php');
require_once('models/hotelinfo.php');
require_once('models/provincia.php');

$id_usuario = $_SESSION['id_usuarios'];
$id_hotel = (int)($_GET['id_hotel'] ?? 0);
if (!$id_hotel) {
    header('Location: index.php?page=proveedores_perfil');
    exit;
}

$hotelModel = new Hotel();
$hotelInfoModel = new HotelInfo();
$hotelInfoModel->setRela_hotel($id_hotel);
$provinciaModel = new Provincia();

if (!$hotelModel->verificar_propietario($id_hotel, $id_usuario)) {
    header('Location: index.php?page=proveedores_perfil&message=Acceso denegado.');
    exit;
}

$hotelData = $hotelModel->traer_hotel($id_hotel)[0] ?? null;
$hotelInfoData = $hotelInfoModel->traer_por_hotel($id_hotel)[0] ?? null;
$provincias = $provinciaModel->traer_provincias();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hotelModel->setId_hotel($id_hotel)
        ->setHotel_nombre($_POST['hotel_nombre'])
        ->setRela_provincia($_POST['rela_provincia'])
        ->setRela_ciudad($_POST['rela_ciudad']);

    if (!empty($_FILES['imagen_principal']['tmp_name'])) {
        $ext = pathinfo($_FILES['imagen_principal']['name'], PATHINFO_EXTENSION);
        $nombreArchivo = "hotel_{$id_hotel}.".$ext;
        move_uploaded_file($_FILES['imagen_principal']['tmp_name'], "assets/images/$nombreArchivo");
        $hotelModel->setImagen_principal($nombreArchivo);
    }

    $hotelModel->actualizar();

    $hotelInfoModel->setDireccion($_POST['direccion'])
        ->setDescripcion($_POST['descripcion'])
        ->setServicios($_POST['servicios'])
        ->setPoliticas_cancelacion($_POST['politicas_cancelacion'])
        ->setReglas($_POST['reglas']);

    $fotosExistentes = $hotelInfoData['fotos'] ? json_decode($hotelInfoData['fotos'], true) : [];
    if (!empty($_FILES['fotos']['tmp_name'][0])) {
        foreach ($_FILES['fotos']['tmp_name'] as $i => $tmp) {
            $ext = pathinfo($_FILES['fotos']['name'][$i], PATHINFO_EXTENSION);
            $nombreFoto = "hotel_{$id_hotel}_{$i}.".$ext;
            move_uploaded_file($tmp, "assets/images/$nombreFoto");
            $fotosExistentes[] = $nombreFoto;
        }
    }
    $hotelInfoModel->setFotos(json_encode($fotosExistentes));

    if ($hotelInfoData) {
        $hotelInfoModel->actualizar();
    } else {
        $hotelInfoModel->guardar();
    }

    header("Location: index.php?page=proveedores_perfil&message=Hotel actualizado correctamente.&status=success");
    exit;
}
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
        <div>
          <label for="hotel_nombre">Nombre del Hotel</label>
          <input type="text" id="hotel_nombre" name="hotel_nombre" value="<?= htmlspecialchars($hotelData['hotel_nombre'] ?? '') ?>" required>
        </div>

        <div>
          <label for="rela_provincia">Provincia</label>
          <select id="rela_provincia" name="rela_provincia" required>
            <option value="">Seleccionar provincia...</option>
            <?php foreach ($provincias as $prov): 
                $selected = ($prov['id_provincia'] == ($hotelData['rela_provincia'] ?? '')) ? 'selected' : '';
            ?>
              <option value="<?= $prov['id_provincia'] ?>" <?= $selected ?>><?= htmlspecialchars($prov['nombre']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label for="rela_ciudad">Ciudad</label>
          <select id="rela_ciudad" name="rela_ciudad" required>
            <option value="">Seleccionar ciudad...</option>
            <?php
            if ($hotelData['rela_provincia'] ?? false) {
                $ciudades = $provinciaModel->traer_ciudades_por_provincia($hotelData['rela_provincia']);
                foreach ($ciudades as $c) {
                    $selected = ($c['id_ciudad'] == ($hotelData['rela_ciudad'] ?? '')) ? 'selected' : '';
                    echo "<option value='{$c['id_ciudad']}' $selected>{$c['nombre']}</option>";
                }
            }
            ?>
          </select>
        </div>

        <div>
          <label for="direccion">Dirección</label>
          <input type="text" id="direccion" name="direccion" value="<?= htmlspecialchars($hotelInfoData['direccion'] ?? '') ?>">
        </div>

        <div>
          <label for="imagen_principal">Imagen principal</label>
          <?php if (!empty($hotelData['imagen_principal'])): ?>
            <img src="assets/images/<?= $hotelData['imagen_principal'] ?>" style="width:150px;">
          <?php endif; ?>
          <input type="file" id="imagen_principal" name="imagen_principal" accept="image/*">
        </div>

        <div class="grid" style="grid-column: 1 / -1;">
          <label for="descripcion">Descripción</label>
          <textarea id="descripcion" name="descripcion"><?= htmlspecialchars($hotelInfoData['descripcion'] ?? '') ?></textarea>
        </div>

        <div class="grid" style="grid-column: 1 / -1;">
          <label for="servicios">Servicios</label>
          <textarea id="servicios" name="servicios"><?= htmlspecialchars($hotelInfoData['servicios'] ?? '') ?></textarea>
        </div>

        <div>
          <label for="politicas_cancelacion">Políticas de cancelación</label>
          <textarea id="politicas_cancelacion" name="politicas_cancelacion"><?= htmlspecialchars($hotelInfoData['politicas_cancelacion'] ?? '') ?></textarea>
        </div>

        <div>
          <label for="reglas">Reglas</label>
          <textarea id="reglas" name="reglas"><?= htmlspecialchars($hotelInfoData['reglas'] ?? '') ?></textarea>
        </div>

        <div class="grid" style="grid-column: 1 / -1;">
          <label for="fotos">Fotos adicionales</label>
          <input type="file" id="fotos" name="fotos[]" accept="image/*" multiple>
          <small>Podes seleccionar varias imágenes a la vez</small>

          <?php
          if (!empty($hotelInfoData['fotos'])):
              $fotosArray = json_decode($hotelInfoData['fotos'], true);
              foreach ($fotosArray as $foto):
          ?>
              <img src="assets/images/<?= $foto ?>" style="width:100px; margin:5px;">
          <?php
              endforeach;
          endif;
          ?>
        </div>

        <div class="actions" style="grid-column: 1 / -1;">
          <a href="index.php?page=proveedores_perfil" class="btn secondary">Cancelar</a>
          <button type="submit" class="btn">Guardar cambios</button>
        </div>
      </form>

    </div>
  </div>
</main>

<script src="assets/js/hotel_carga.js"></script>
</body>
</html>
