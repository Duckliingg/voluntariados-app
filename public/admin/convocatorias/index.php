<?php
// public/admin/convocatorias/index.php
// Punto de entrada - Solo llama al controlador

require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../app/Services/AuthService.php';
require_once __DIR__ . '/../../../app/Controllers/ConvocatoriaController.php';

$controller = new ConvocatoriaController();
$controller->index();
