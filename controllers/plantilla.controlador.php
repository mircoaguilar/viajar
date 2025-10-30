<?php

class PlantillaControlador {

    public function traer_plantilla() {
        $ruta = __DIR__ . '/../views/plantilla.php';

        if (file_exists($ruta)) {
            return include($ruta);
        } else {
            echo "Error: La plantilla no fue encontrada en $ruta";
            return false;
        }
    }

}
