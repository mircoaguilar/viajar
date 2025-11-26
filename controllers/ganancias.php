<?php
require_once __DIR__ . '/../models/ganancias.php';

$gananciaModel = new Ganancias();
$lista = $gananciaModel->listarGanancias();

include __DIR__ . '/../views/paginas/ganancias_listado.php';
