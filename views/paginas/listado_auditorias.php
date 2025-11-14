<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['id_perfiles']) || !in_array($_SESSION['id_perfiles'], [2])) {
    header("Location: ../../index.php?page=login&message=Acceso no autorizado&status=danger");
    exit;
}

require_once(__DIR__ . '/../componentes/cabecera.admin.php');
require_once(__DIR__ . '/../../models/auditoria.php');
require_once(__DIR__ . '/../../models/usuarios.php');

$page_size = 10;
$current_page = isset($_GET['current_page']) ? max(0, (int)$_GET['current_page']) : 0;

$usuario_filtro = $_GET['usuario'] ?? '';
$accion_filtro = $_GET['accion'] ?? '';
$fecha_desde = $_GET['fecha_desde'] ?? '';
$fecha_hasta = $_GET['fecha_hasta'] ?? '';

$auditoriaModel = new Auditoria();
$auditoriaModel->page_size = $page_size;
$auditoriaModel->current_page = $current_page;

$total_rows = $auditoriaModel->contar($usuario_filtro, $accion_filtro, $fecha_desde, $fecha_hasta);
$total_pages = max(1, ceil($total_rows / $page_size));

$auditorias = $auditoriaModel->filtrar($usuario_filtro, $accion_filtro, $fecha_desde, $fecha_hasta);

$usuarioModel = new Usuario();
$usuarios = $usuarioModel->traer_usuarios();
?>

<div class="contenido-dashboard">
    <h1>Historial de Auditorías</h1>

    <section class="dashboard-section">
        <h2>Filtrar auditorías</h2>
        <form class="form-filtros" method="get">
            <div class="filtros-container">
                <input type="hidden" name="page" value="auditorias">
                
                <div class="filtro-item">
                    <label for="usuario">Usuario:</label>
                    <select id="usuario" name="usuario" class="select2">
                        <option value="">Todos</option>
                        <?php foreach($usuarios as $u): ?>
                            <option value="<?= (int)$u['id_usuarios'] ?>" <?= $usuario_filtro == $u['id_usuarios'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($u['usuarios_nombre_usuario']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filtro-item">
                    <label for="accion">Acción:</label>
                    <select id="accion" name="accion" class="accion-select">
                        <option value="">Todas</option>
                        <option value="Alta" <?= $accion_filtro == 'Alta' ? 'selected' : '' ?>>Alta</option>
                        <option value="Actualización" <?= $accion_filtro == 'Actualización' ? 'selected' : '' ?>>Modificación</option>
                        <option value="Baja" <?= $accion_filtro == 'Baja' ? 'selected' : '' ?>>Eliminación</option>
                        <option value="Otros" <?= $accion_filtro == 'Otros' ? 'selected' : '' ?>>Otros</option>
                        <option value="reserva_pago" <?= $accion_filtro == 'reserva_pago' ? 'selected' : '' ?>>Reservas y Pagos</option>
                    </select>
                </div>

                <div class="filtro-item filtro-fechas-separadas">
                    <label for="fecha_desde">Desde:</label>
                    <input type="text" id="fecha_desde" name="fecha_desde" class="flatpickr-input" readonly value="<?= htmlspecialchars($fecha_desde) ?>">
                </div>

                <div class="filtro-item filtro-fechas-separadas">
                    <label for="fecha_hasta">Hasta:</label>
                    <input type="text" id="fecha_hasta" name="fecha_hasta" class="flatpickr-input" readonly value="<?= htmlspecialchars($fecha_hasta) ?>">
                </div>
                
                <div class="filtro-item acciones">
                    <button type="submit" class="btn-filtrar" title="Filtrar"><i class="fa fa-search"></i></button>
                    <a href="?page=auditorias" class="btn-limpiar" title="Limpiar filtros">
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

        <nav class="paginacion">
            <ul>
                <li>
                    <a href="?page=listado_auditorias&current_page=<?= max(0, $current_page-1) ?>&usuario=<?= $usuario_filtro ?>&accion=<?= $accion_filtro ?>&fecha_desde=<?= $fecha_desde ?>&fecha_hasta=<?= $fecha_hasta ?>"
                       class="<?= ($current_page <= 0) ? 'disabled' : '' ?>">Atrás</a>
                </li>

                <?php
                $rango = 2;
                for($i = max(0, $current_page - $rango); $i <= min($total_pages-1, $current_page + $rango); $i++): ?>
                    <li>
                        <a href="?page=listado_auditorias&current_page=<?= $i ?>&usuario=<?= $usuario_filtro ?>&accion=<?= $accion_filtro ?>&fecha_desde=<?= $fecha_desde ?>&fecha_hasta=<?= $fecha_hasta ?>"
                           class="<?= ($i == $current_page) ? 'active' : '' ?>"><?= $i+1 ?></a>
                    </li>
                <?php endfor; ?>

                <li>
                    <a href="?page=listado_auditorias&current_page=<?= min($total_pages-1, $current_page+1) ?>&usuario=<?= $usuario_filtro ?>&accion=<?= $accion_filtro ?>&fecha_desde=<?= $fecha_desde ?>&fecha_hasta=<?= $fecha_hasta ?>"
                       class="<?= ($current_page >= $total_pages-1) ? 'disabled' : '' ?>">Siguiente</a>
                </li>
            </ul>
        </nav>

    </section>
</div>

<link rel="stylesheet" href="/viajar/assets/css/auditorias.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

<script src="/viajar/assets/js/listado_auditorias.js"></script>
