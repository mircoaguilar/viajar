<?php
require_once ('models/usuarios.php');
require_once ('controllers/plantilla.controlador.php');
require_once ('models/perfiles.php');
require_once ('models/tipo_pagos.php');
require_once ('models/tipo_contactos.php');

$plantilla = new PlantillaControlador();
$plantilla -> traer_plantilla();


