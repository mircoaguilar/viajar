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
            <a href="#" 
               data-page="<?= max(0, $current_page-1) ?>"
               class="pagina-btn <?= ($current_page <= 0) ? 'disabled' : '' ?>">Atrás</a>
        </li>

        <?php $rango = 2; ?>
        <?php for($i = max(0, $current_page - $rango); $i <= min($total_pages-1, $current_page + $rango); $i++): ?>
            <li>
                <a href="#" 
                   data-page="<?= $i ?>"
                   class="pagina-btn <?= ($i == $current_page) ? 'active' : '' ?>"><?= $i+1 ?></a>
            </li>
        <?php endfor; ?>

        <li>
            <a href="#"
               data-page="<?= min($total_pages-1, $current_page+1) ?>"
               class="pagina-btn <?= ($current_page >= $total_pages-1) ? 'disabled' : '' ?>">Siguiente</a>
        </li>
    </ul>
</nav>
