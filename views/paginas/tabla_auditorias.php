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
    <p class="info-paginacion">Mostrando registros **<?= $current_page * $page_size + 1 ?>** a **<?= min($total_rows, ($current_page + 1) * $page_size) ?>** de **<?= $total_rows ?>**. Página **<?= $current_page + 1 ?>** de **<?= $total_pages ?>**.</p>
    
    <?php if ($total_pages > 1): ?>
        <ul>
            <li>
                <a href="#" data-page="<?= max(0, $current_page - 1) ?>"
                   class="btn-paginacion <?= ($current_page <= 0) ? 'disabled' : '' ?>">Atrás</a>
            </li>

            <?php
            $rango = 2;
            $start = max(0, $current_page - $rango);
            $end = min($total_pages - 1, $current_page + $rango);

            if ($start > 0) {
                echo '<li><a href="#" data-page="0" class="btn-paginacion">1</a></li>';
                if ($start > 1) {
                    echo '<li><span>...</span></li>';
                }
            }

            for($i = $start; $i <= $end; $i++): ?>
                <li>
                    <a href="#" data-page="<?= $i ?>"
                       class="btn-paginacion <?= ($i == $current_page) ? 'active' : '' ?>"><?= $i + 1 ?></a>
                </li>
            <?php endfor;

            if ($end < $total_pages - 1) {
                if ($end < $total_pages - 2) {
                    echo '<li><span>...</span></li>';
                }
                echo '<li><a href="#" data-page="' . ($total_pages - 1) . '" class="btn-paginacion">' . $total_pages . '</a></li>';
            }
            ?>

            <li>
                <a href="#" data-page="<?= min($total_pages - 1, $current_page + 1) ?>"
                   class="btn-paginacion <?= ($current_page >= $total_pages - 1) ? 'disabled' : '' ?>">Siguiente</a>
            </li>
        </ul>
    <?php endif; ?>
</nav>