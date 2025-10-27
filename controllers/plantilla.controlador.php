<?php

class PlantillaControlador {

    // Traer la plantilla
    public function traer_plantilla() {
        $ruta = __DIR__ . '/../views/plantilla.php';

        if (file_exists($ruta)) {
            return include($ruta);
        } else {
            // Manejo de error simple si la vista no existe
            echo "Error: La plantilla no fue encontrada en $ruta";
            return false;
        }
    }

}
