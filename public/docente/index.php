<?php
// public/docente/index.php
require_once __DIR__ . '/../../app/Controllers/DocenteController.php';
session_start();

// Verificar autenticación
if (!isset($_SESSION['docente_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$controller = new DocenteController();

// Routing básico
$action = $_GET['action'] ?? 'dashboard';

switch ($action) {
    case 'dashboard':
        $controller->dashboard();
        break;
    case 'convocatorias':
        $controller->listarConvocatorias();
        break;
    case 'tomar-asistencia':
        $convocatoriaId = $_GET['id'] ?? 0;
        $controller->tomarAsistencia($convocatoriaId);
        break;
    default:
        $controller->dashboard();
}
