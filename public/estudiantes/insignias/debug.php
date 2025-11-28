<?php
session_start();
require_once __DIR__ . '/../../../app/Services/InsigniaService.php';

echo "<h3>Debug de Insignias</h3>";
echo "Usuario ID: " . $_SESSION['estudiante_id'] . "<br><br>";

$insigniaService = new InsigniaService();
$insignias = $insigniaService->obtenerInsigniasUsuario($_SESSION['estudiante_id']);

echo "Total insignias: " . count($insignias) . "<br><br>";

echo "<pre>";
print_r($insignias);
echo "</pre>";
?>
