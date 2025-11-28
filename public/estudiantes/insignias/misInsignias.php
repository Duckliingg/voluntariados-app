<?php
// public/estudiantes/insignias/misInsignias.php

require_once __DIR__ . '/../../../app/Controllers/InsigniaController.php';

session_start();

// Verificar autenticación
if (!isset($_SESSION['estudiante_id'])) {
    header('Location: ../login.php');
    exit;
}

$controller = new InsigniaController();
$controller->misInsignias();