<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../app/Controllers/ConvocatoriaController.php';

$controller = new ConvocatoriaController();
$controller->finalizar();
