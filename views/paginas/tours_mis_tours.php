<?php
require_once('models/Tour.php');
require_once('models/proveedor.php');
require_once('controllers/proveedores/proveedores.controlador.php');

if (!isset($_SESSION['id_usuarios']) || !in_array($_SESSION['id_perfiles'] ?? 0, [13,14])) { 
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
<style>
    .estado { font-weight: bold; }
    .estado-pendiente { color: orange; }
    .estado-aprobado { color: green; }
    .estado-rechazado { color: red; }

    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 10px; border-bottom: 1px solid #ccc; text-align: left; }
    img { width: 100px; height: auto; border-radius: 4px; }
    .actions a, .actions button { margin-right: 5px; }

    .modal { display: none; position: fixed; top:0; left:0; width:100%; height:100%;
             background: rgba(0,0,0,0.5); justify-content: center; align-items: center; }
    .modal-content { background: #fff; padding: 20px; border-radius: 6px; max-height:80%; overflow-y:auto; width:400px; }
    .close { float: right; cursor: pointer; font-size: 18px; font-weight: bold; }
</style>
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
                    <th>Hora</th>
                    <th>Punto de encuentro</th>
                    <th>Dirección</th> 
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($tours as $t): ?>
                <tr>

                    <td>
                        <?php if (!empty($t['imagen_principal'])): ?>
                            <img src="assets/images/<?= htmlspecialchars($t['imagen_principal']) ?>" 
                                alt="Tour <?= htmlspecialchars($t['nombre_tour']) ?>" 
                                style="width:100px; height:auto;">
                        <?php else: ?>
                            <span>Sin imagen</span>
                        <?php endif; ?>
                    </td>

                    <td><?= htmlspecialchars($t['nombre_tour']) ?></td>

                    <td>
                        <?php
                            $duracion = $t['duracion_horas'];
                            list($h, $m) = explode(':', substr($duracion, 0, 5));
                            $textoDuracion = intval($h) . 'h';
                            if (intval($m) > 0) $textoDuracion .= ' ' . intval($m) . 'm';
                            echo $textoDuracion;
                        ?>
                    </td>

                    <td>$<?= number_format($t['precio_por_persona'], 2, ',', '.') ?></td>

                    <td><?= htmlspecialchars(substr($t['hora_encuentro'], 0, 5)) ?> hs</td>

                    <td><?= htmlspecialchars($t['lugar_encuentro']) ?></td>

                    <td><?= htmlspecialchars($t['direccion']) ?></td>

                    <td>
                        <?php
                            $estado = strtolower($t['estado_revision'] ?? 'pendiente');
                            $clase_estado = 'estado-' . $estado;
                            echo "<span class='estado $clase_estado'>" . ucfirst($estado) . "</span>";
                        ?>
                    </td>

                    <td>
                        <?php 
                            $esRechazado = ($estado === 'rechazado');
                            $textoBoton = $esRechazado ? "Volver a enviar" : "Editar";
                        ?>

                        <a href="index.php?page=tours_editar&id_tour=<?= $t['id_tour'] ?>" 
                        class="btn <?= $esRechazado ? 'btn-warning' : '' ?>">
                            <?= $textoBoton ?>
                        </a>

                        <?php if ($estado === 'aprobado'): ?>
                            <a href="index.php?page=tours_stock&id_tour=<?= $t['id_tour'] ?>" 
                            class="btn secondary">Cargar stock</a>
                            <button class="btn secondary ver-stock-btn" 
                                    data-id-tour="<?= $t['id_tour'] ?>">
                                Ver stock
                            </button>
                        <?php else: ?>
                            <a class="btn secondary disabled" 
                            title="Disponible solo cuando el tour esté aprobado">
                                Cargar stock
                            </a>
                            <button class="btn secondary disabled">Ver stock</button>
                        <?php endif; ?>

                        <?php if ($esRechazado && !empty($t['motivo_rechazo'])): ?>
                            <button class="btn secondary"
                                onclick="mostrarMotivo('<?= htmlspecialchars($t['motivo_rechazo']) ?>')">
                                Ver motivo de rechazo
                            </button>
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

<div id="modalMotivo" class="modal">
    <div class="modal-content">
        <button class="modal-close" onclick="cerrarModal()">✖</button>
        <h3 class="modal-title">Motivo del rechazo</h3>
        <p id="textoMotivo" class="modal-text"></p>
        <button class="modal-btn" onclick="cerrarModal()">Entendido</button>
    </div>
</div>

<div id="modal-stock" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Stock del tour</h3>
        <table id="stock-table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Cupos disponibles</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>


<script src="assets/js/mis_tours.js"></script>
</body>
</html>
