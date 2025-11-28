<?php
// public/admin/postulaciones/aprobar.php

require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../app/Services/AuthService.php';
require_once __DIR__ . '/../../../app/Controllers/PostulacionController.php';

$controller = new PostulacionController();
$controller->aprobar();
