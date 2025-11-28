<?php
require_once '../autenticar.php';
require_once __DIR__ . '/../../../app/Core/Database.php';
$conexion = new Conexion();

// Obtener estadísticas para reportes
$stmt = $conexion->pdo->query("
    SELECT 
        COUNT(*) as total_postulaciones,
        SUM(CASE WHEN estado = 'aprobada' THEN 1 ELSE 0 END) as aprobadas,
        SUM(CASE WHEN estado = 'rechazada' THEN 1 ELSE 0 END) as rechazadas,
        SUM(CASE WHEN estado = 'pendiente' THEN 1 ELSE 0 END) as pendientes
    FROM postulaciones
");
$estadisticas = $stmt->fetch(PDO::FETCH_ASSOC);

// Obtener postulaciones por convocatoria
$stmt = $conexion->pdo->query("
    SELECT c.titulo, COUNT(p.id) as total_postulaciones
    FROM convocatorias c 
    LEFT JOIN postulaciones p ON c.id = p.convocatoria_id 
    GROUP BY c.id, c.titulo 
    ORDER BY total_postulaciones DESC
");
$postulaciones_por_convocatoria = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes</title>
    <link rel="stylesheet" href="../../assets/css/estilo.css">
</head>
<body>
    <header>
        <div class="container header-content">
            <div class="logo">
                <div class="logo-icon"><img src="../../assets/img/logo-upt.png" alt="Logo UPT"></div>
                <h1>Reportes del Sistema</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="../index.php">Panel Admin</a></li>
                    <li><a href="../logout.php" class="btn">Cerrar Sesion</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="section">
        <div class="container">
            <h2 class="section-title">Reportes de Participacion</h2>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $estadisticas['total_postulaciones']; ?></div>
                    <div class="stat-label">Total Postulaciones</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $estadisticas['aprobadas']; ?></div>
                    <div class="stat-label">Aprobadas</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $estadisticas['rechazadas']; ?></div>
                    <div class="stat-label">Rechazadas</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $estadisticas['pendientes']; ?></div>
                    <div class="stat-label">Pendientes</div>
                </div>
            </div>

            <div class="dashboard">
                <h3>Postulaciones por Convocatoria</h3>
                <table class="tabla">
                    <thead>
                        <tr>
                            <th>Convocatoria</th>
                            <th>Total Postulaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($postulaciones_por_convocatoria as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['titulo']); ?></td>
                            <td><?php echo $item['total_postulaciones']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="dashboard mt-2rem">
                <h3>Acciones</h3>
                <div class="flex-gap">
                    <button onclick="window.print()" class="btn">Imprimir Reporte</button>
                    <a href="../index.php" class="btn btn-secondary">Volver al Panel</a>
                </div>
            </div>
        </div>
    </section>
</body>
</html>