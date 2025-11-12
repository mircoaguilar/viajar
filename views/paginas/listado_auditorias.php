<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['id_perfiles']) || !in_array($_SESSION['id_perfiles'], [2])) {
    header("Location: ../../index.php?page=login&message=Acceso no autorizado&status=danger");
    exit;
}

require_once(__DIR__ . '/../componentes/cabecera.admin.php');
require_once(__DIR__ . '/../../models/auditoria.php');
require_once(__DIR__ . '/../../models/usuarios.php');

$auditoriaModel = new Auditoria();
$auditorias = $auditorias ?? $auditoriaModel->traer_todas();

$usuarioModel = new Usuario();
$usuarios = $usuarioModel->traer_usuarios();

$acciones = array_column($auditoriaModel->traer_todas(), 'accion');
$acciones = array_unique($acciones);
sort($acciones);
?>

<div class="contenido-dashboard">
    <h1>Historial de Auditorías</h1> <section class="dashboard-section">
        <h2>Filtrar auditorías</h2>

        <form method="POST" action="index.php?page=listado_auditorias" class="form-filtros">
            <div class="filtros-container">

                <div class="filtro-item">
                    <label for="usuario">Usuario:</label>
                    <select id="usuario" name="usuario" class="select2">
                        <option value="">Todos</option>
                        <?php foreach($usuarios as $u): ?>
                            <option value="<?= (int)$u['id_usuarios'] ?>"
                                <?= isset($_POST['usuario']) && $_POST['usuario'] == $u['id_usuarios'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($u['usuarios_nombre_usuario']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filtro-item">
                    <label for="accion">Acción:</label>
                    <select id="accion" name="accion" style="width: 180px;">
                        <option value="">Todas</option>
                        <option value="Alta" <?= (isset($_POST['accion']) && $_POST['accion'] == 'Alta') ? 'selected' : '' ?>>Alta</option>
                        <option value="Modificación" <?= (isset($_POST['accion']) && $_POST['accion'] == 'Modificación') ? 'selected' : '' ?>>Modificación</option>
                        <option value="Eliminación" <?= (isset($_POST['accion']) && $_POST['accion'] == 'Eliminación') ? 'selected' : '' ?>>Eliminación</option>
                        <option value="Otros" <?= (isset($_POST['accion']) && $_POST['accion'] == 'Otros') ? 'selected' : '' ?>>Otros</option>
                    </select>
                </div>


                <div class="filtro-item filtro-fechas-separadas">
                    <label for="fecha_desde">Desde:</label>
                    <input type="text" id="fecha_desde" name="fecha_desde"
                        value="<?= htmlspecialchars($_POST['fecha_desde'] ?? '') ?>" class="flatpickr-input" readonly>
                </div>

                <div class="filtro-item filtro-fechas-separadas">
                    <label for="fecha_hasta">Hasta:</label>
                    <input type="text" id="fecha_hasta" name="fecha_hasta"
                        value="<?= htmlspecialchars($_POST['fecha_hasta'] ?? '') ?>" class="flatpickr-input" readonly>
                </div>
                
                <div class="filtro-item acciones">
                    <button type="submit" class="btn-filtrar"><i class="fa fa-search"></i></button>
                    <a href="index.php?page=auditorias&action=listar_auditorias" class="btn-limpiar">
                        <i class="fa fa-times"></i>
                    </a>
                </div>

            </div>
        </form>
    </section>

    <section class="dashboard-section">
        <h2>Registros</h2>
        <table class="tabla-datos">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Perfil</th>
                    <th>Acción</th>
                    <th>Descripción</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($auditorias)): ?>
                    <?php foreach ($auditorias as $a): ?>
                        <tr>
                            <td><?= $a['id_auditoria'] ?></td>
                            <td><?= htmlspecialchars($a['usuario_nombre'] ?? 'Desconocido') ?></td>
                            <td><?= htmlspecialchars($a['perfil_nombre'] ?? '') ?></td>
                            <td><?= htmlspecialchars($a['accion']) ?></td>
                            <td><?= htmlspecialchars($a['descripcion']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($a['fecha'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="no-datos">No se encontraron auditorías.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</div>

<link rel="stylesheet" href="/viajar/assets/css/auditorias.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>


<script>
document.addEventListener("DOMContentLoaded", function() {
    $('.select2').select2({
        placeholder: "Seleccione un usuario",
        allowClear: true
    });

    flatpickr(".flatpickr-input", {
        dateFormat: "Y-m-d",
        locale: "es" 
    });
});
</script>