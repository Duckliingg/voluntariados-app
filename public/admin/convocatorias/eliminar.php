<?php
// public/admin/convocatorias/eliminar.php

require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../app/Services/AuthService.php';
require_once __DIR__ . '/../../../app/Controllers/ConvocatoriaController.php';

$controller = new ConvocatoriaController();
$controller->eliminar();
