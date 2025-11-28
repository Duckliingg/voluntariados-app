<?php
// views/estudiantes/dashboard.php

$userType = 'estudiante';
include __DIR__ . '/../layouts/header.php';

// Obtener insignias del estudiante
$totalInsignias = $totalInsignias ?? 0;
$insigniasRecientes = $insigniasRecientes ?? [];
?>

<h2 class="section-title">Bienvenido, <?php echo htmlspecialchars($_SESSION['estudiante_nombre']); ?></h2>

<!-- Tarjetas de estadísticas - AGREGAR INSIGNIAS -->
<div class="stats-grid">
    <div class="stat-card">
        <h3>Mis Postulaciones</h3>
        <p class="stat-number"><?php echo $totalPostulaciones; ?></p>
        <a href="historial/index.php" class="btn-pequeño">Ver historial</a>
    </div>
    
    <div class="stat-card">
        <h3>Aprobadas</h3>
        <p class="stat-number"><?php echo $postulacionesAprobadas; ?></p>
        <a href="historial/index.php" class="btn-pequeño">Ver</a>
    </div>
    
    <div class="stat-card">
        <h3>Mis Insignias</h3>
        <p class="stat-number"><?php echo $totalInsignias; ?></p>
        <a href="insignias/misInsignias.php" class="btn-pequeño">Ver insignias</a>
    </div>
</div>

<!-- Sección de Insignias Recientes -->
<?php if ($totalInsignias > 0): ?>
<div class="mt-30">
    <h3>Mis Insignias Recientes</h3>
    <div class="insignias-grid">
        <?php foreach($insigniasRecientes as $insignia): ?>
        <div class="insignia-card">
            <div class="insignia-icon" style="color: <?php echo $insignia['color'] ?? '#3498db'; ?>">
                <i class="fas fa-medal"></i>
            </div>
            <div class="insignia-info">
                <h4><?php echo htmlspecialchars($insignia['nombre']); ?></h4>
                <p><?php echo htmlspecialchars($insignia['descripcion']); ?></p>
                <span class="insignia-badge"><?php echo ucfirst($insignia['nivel']); ?></span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="text-center-mt-20">
        <a href="insignias/misInsignias.php" class="btn">Ver todas mis insignias</a>
    </div>
</div>
<?php else: ?>
<div class="mt-30 alert-info">
    <h3>¡Gana tu primera insignia!</h3>
    <p>Participa en actividades de voluntariado para obtener insignias y reconocimientos.</p>
    <a href="convocatorias/index.php" class="btn">Explorar Convocatorias</a>
</div>
<?php endif; ?>

<!-- Convocatorias disponibles -->
<h3 class="mt-30">Convocatorias Disponibles</h3>
<?php if(empty($convocatorias)): ?>
    <p>No hay convocatorias disponibles en este momento.</p>
<?php else: ?>
    <div class="convocatorias-grid">
        <?php foreach($convocatorias as $conv): ?>
        <div class="convocatoria-card">
            <?php if(!empty($conv->imagen)): ?>
                <img src="<?php echo $baseUrl; ?>uploads/<?php echo htmlspecialchars($conv->imagen); ?>" 
                     alt="<?php echo htmlspecialchars($conv->titulo); ?>"
                     >
            <?php endif; ?>
            
            <div class="convocatoria-card-body">
                <h3><?php echo htmlspecialchars($conv->titulo); ?></h3>
                <p><?php echo nl2br(htmlspecialchars(substr($conv->descripcion, 0, 100))); ?>...</p>
                
                    <div class="m-15-0">
                        <p><strong>Inicio:</strong> <?php echo $conv->fecha_inicio; ?></p>
                        <p><strong>Fin:</strong> <?php echo $conv->fecha_fin; ?></p>
                        <p><strong>Cupos:</strong> <?php echo $conv->cupos; ?></p>
                        <p><strong>Lugar:</strong> <?php echo htmlspecialchars($conv->lugar); ?></p>
                        <p><strong>Responsable:</strong> <?php echo htmlspecialchars($conv->contacto_responsable); ?></p>
                        <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($conv->telefono_contacto); ?></p>
                        <p><strong>WhatsApp:</strong> 
                            <a href="<?php echo htmlspecialchars($conv->whatsapp_grupo); ?>" target="_blank">Unirse</a>
                        </p>
                        <p><strong>Tipo:</strong> <?php echo htmlspecialchars($conv->tipo_voluntariado); ?></p>
                    </div>
                
                <a href="convocatorias/postular.php?id=<?php echo $conv->id; ?>" class="btn" class="btn-full-width">
                    Postularme
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="text-center-mt-20">
        <a href="convocatorias/index.php" class="btn">Ver todas las convocatorias</a>
    </div>
<?php endif; ?>

<!-- Mis últimas postulaciones -->
<h3 class="mt-30">Mis Últimas Postulaciones</h3>
<?php if(empty($misPostulaciones)): ?>
    <p>Aún no has realizado ninguna postulación.</p>
    <a href="convocatorias/index.php" class="btn">Explorar Convocatorias</a>
<?php else: ?>
    <table class="tabla">
        <thead>
            <tr>
                <th>Convocatoria</th>
                <th>Fecha Postulación</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($misPostulaciones as $post): ?>
            <tr>
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

<style>
.insignias-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.insignia-card {
    display: flex;
    align-items: center;
    padding: 20px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    background: white;
    transition: transform 0.2s;
}

.insignia-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.insignia-icon {
    font-size: 2rem;
    margin-right: 15px;
}

.insignia-info h4 {
    margin: 0 0 5px 0;
    color: #333;
}

.insignia-info p {
    margin: 0 0 10px 0;
    color: #666;
    font-size: 0.9rem;
}

.insignia-badge {
    background: #f8f9fa;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.8rem;
    color: #495057;
}

.alert-info {
    background: #e3f2fd;
    border: 1px solid #bbdefb;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
}

.alert-info h3 {
    margin-top: 0;
    color: #1976d2;
}
</style>

<?php include __DIR__ . '/../layouts/footer.php'; ?>