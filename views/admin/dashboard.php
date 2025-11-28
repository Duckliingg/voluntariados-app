<?php
// views/admin/dashboard.php
// Variables: estadísticas y datos del dashboard

$userType = 'admin';
include __DIR__ . '/../layouts/header.php';
?>

<h2 class="section-title">Bienvenido, <?php echo htmlspecialchars($_SESSION['admin_nombre']); ?></h2>

<!-- Tarjetas de estadísticas -->
<div class="stats-grid">
    <div class="stat-card">
        <h3>Total Convocatorias</h3>
        <p class="stat-number"><?php echo $totalConvocatorias; ?></p>
        <a href="convocatorias/index.php" class="btn-pequeño">Ver todas</a>
    </div>
    
    <div class="stat-card">
        <h3>Total Postulaciones</h3>
        <p class="stat-number"><?php echo $totalPostulaciones; ?></p>
        <a href="postulaciones/index.php" class="btn-pequeño">Ver todas</a>
    </div>
    
    <div class="stat-card">
        <h3>Postulaciones Pendientes</h3>
        <p class="stat-number"><?php echo $postulacionesPendientes; ?></p>
        <a href="postulaciones/index.php" class="btn-pequeño">Revisar</a>
    </div>
    
    <div class="stat-card">
        <h3>Postulaciones Aprobadas</h3>
        <p class="stat-number"><?php echo $postulacionesAprobadas; ?></p>
        <a href="postulaciones/index.php" class="btn-pequeño">Ver</a>
    </div>
</div>

<!-- Convocatorias recientes -->
<h3 class="mt-30">Convocatorias Recientes</h3>
<?php if(empty($convocatoriasRecientes)): ?>
    <p>No hay convocatorias.</p>
<?php else: ?>
    <table class="tabla">
        <thead>
            <tr>
                <th>Título</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Cupos</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($convocatoriasRecientes as $conv): ?>
            <tr>
                <td><?php echo htmlspecialchars($conv->titulo); ?></td>
                <td><?php echo $conv->fecha_inicio; ?></td>
                <td><?php echo $conv->fecha_fin; ?></td>
                <td><?php echo $conv->cupos; ?></td>
                <td>
                    <span class="<?php echo $conv->estado == 'activa' ? 'estado-activa' : 'estado-inactiva'; ?>">
                        <?php echo ucfirst($conv->estado); ?>
                    </span>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<!-- Postulaciones recientes -->
<h3 class="mt-30">Postulaciones Recientes</h3>
<?php if(empty($postulacionesRecientes)): ?>
    <p>No hay postulaciones.</p>
<?php else: ?>
    <table class="tabla">
        <thead>
            <tr>
                <th>Estudiante</th>
                <th>Convocatoria</th>
                <th>Fecha</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($postulacionesRecientes as $post): ?>
            <tr>
                <td><?php echo htmlspecialchars($post->estudiante_nombre); ?></td>
                <td><?php echo htmlspecialchars($post->convocatoria_titulo); ?></td>
                <td><?php echo $post->fecha_postulacion; ?></td>
                <td>
                    <span class="<?php 
                        echo $post->estado == 'aprobada' ? 'estado-activa' : 
                            ($post->estado == 'rechazada' ? 'estado-inactiva' : ''); 
                    ?>">
                        <?php echo ucfirst($post->estado); ?>
                    </span>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

