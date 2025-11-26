<?php
// El controlador ya te pasó $lista
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ganancias - Panel Admin</title>

    <style>
        body {
            font-family: Arial;
            background: #f2f2f2;
        }
        h1 {
            text-align: center;
            color: #2943b9;
        }
        table {
            width: 95%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background: #2943b9;
            color: white;
        }
        .ganancia-pos {
            color: green;
            font-weight: bold;
        }
        .ganancia-neg {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h1>Listado de Ganancias</h1>

<table>
    <tr>
        <th>ID</th>
        <th>ID Reserva</th>
        <th>ID Detalle</th>
        <th>Monto Venta</th>
        <th>Costo Proveedor</th>
        <th>Costo Transacción</th>
        <th>Ganancia Neta</th>
        <th>Fecha</th>
        <th>Estado Reserva</th>
    </tr>

    <?php if (!empty($lista)): ?>
        <?php foreach ($lista as $g): ?>
            <tr>
                <td><?= $g['id_ganancia'] ?></td>
                <td><?= $g['rela_reserva'] ?></td>
                <td><?= $g['rela_detalle_reserva'] ?></td>
                <td>$<?= number_format($g['monto_venta'], 2) ?></td>
                <td>$<?= number_format($g['costo_proveedor'], 2) ?></td>
                <td>$<?= number_format($g['costo_transaccion'], 2) ?></td>

                <td class="<?= $g['ganancia_neta'] >= 0 ? 'ganancia-pos' : 'ganancia-neg' ?>">
                    $<?= number_format($g['ganancia_neta'], 2) ?>
                </td>

                <td><?= $g['fecha_registro'] ?></td>
                <td><?= ucfirst($g['reservas_estado']) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="9" style="text-align:center; color:red;">
                No hay registros de ganancias.
            </td>
        </tr>
    <?php endif; ?>

</table>

</body>
</html>
