<?php

require_once(__DIR__ . '/../../models/ganancia.php');
require_once(__DIR__ . '/../../models/reserva.php');
require_once(__DIR__ . '/../../models/pago.php');

class GananciasControlador {
    public function listar() {
        if ($_SESSION['id_perfiles'] != 2) { 
            header("Location: /index.php?page=login&message=Acceso no autorizado.");
            exit;
        }

        $gananciaModel = new Ganancia();
        $ganancias = $gananciaModel->obtenerTodasLasGanancias();
        require_once(__DIR__ . '/../../views/ganancias/listar.php'); 
    }
    public function verGanancias($id_reserva) {
        if ($_SESSION['id_perfiles'] != 2) { 
            header("Location: /index.php?page=login&message=Acceso no autorizado.");
            exit;
        }

        $gananciaModel = new Ganancia();
        $ganancia = $gananciaModel->obtenerGananciasPorReserva($id_reserva); 
        require_once(__DIR__ . '/../../views/ganancias/detalles.php');
    }

}
?>
