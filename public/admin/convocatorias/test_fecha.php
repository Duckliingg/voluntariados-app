<?php
// Fecha de hoy
$hoy = date('Y-m-d');
echo "Hoy: " . $hoy . "<br>";

// Fecha de la convocatoria
$fecha_fin = '2025-11-25';
echo "Fecha fin convocatoria: " . $fecha_fin . "<br>";

// Comparación
echo "¿fecha_fin <= hoy? " . ($fecha_fin <= $hoy ? 'SÍ' : 'NO') . "<br>";

// Estado
$estado = 'activa';
echo "Estado: " . $estado . "<br>";

// ¿Puede finalizarse?
$puedeFinalizarse = ($estado == 'activa' && $fecha_fin <= $hoy);
echo "¿Puede finalizarse? " . ($puedeFinalizarse ? 'SÍ' : 'NO');
?>
