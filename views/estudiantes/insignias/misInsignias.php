<?php
// views/estudiantes/insignias/misInsignias.php
$userType = 'estudiante';
include __DIR__ . '/../../layouts/header.php';
?>

<div class="insignias-page">
    <h2 class="section-title">Mis Insignias</h2>
    <p class="subtitle">Reconocimientos obtenidos por tu participación en voluntariados</p>

    <?php if (empty($insignias)): ?>
        <div class="empty-state">
            <div class="empty-icon">🏅</div>
            <h3>Aún no tienes insignias</h3>
            <p>Participa en más actividades de voluntariado para obtener insignias.</p>
            <a href="../index.php" class="btn">Volver al Panel</a>
        </div>
    <?php else: ?>
        <div class="insignias-grid-full">
            <?php foreach ($insignias as $insignia): ?>
            <div class="insignia-card-full">
                <div class="insignia-header" style="background: <?php echo htmlspecialchars($insignia['color']); ?>">
                    <div class="insignia-icon-large">
                        <?php echo htmlspecialchars($insignia['icono']); ?>
                    </div>
                </div>
                
                <div class="insignia-body">
                    <h3><?php echo htmlspecialchars($insignia['nombre']); ?></h3>
                    <p class="insignia-description"><?php echo htmlspecialchars($insignia['descripcion']); ?></p>
                    
                    <div class="insignia-metadata">
                        <span class="badge-categoria" style="background: <?php echo htmlspecialchars($insignia['color']); ?>">
                            <?php echo ucfirst(htmlspecialchars($insignia['categoria'])); ?>
                        </span>
                        
                        <div class="fecha-obtencion">
                            Obtenida: <?php echo date('d/m/Y', strtotime($insignia['fecha_obtenida'])); ?>
                        </div>
                        
                        <?php if (!empty($insignia['convocatoria_id'])): ?>
                        <div class="convocatoria-vinculada">
                            Convocatoria ID: <?php echo $insignia['convocatoria_id']; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="insignias-summary">
            <h3>Total de insignias obtenidas: <?php echo count($insignias); ?></h3>
            <p>¡Sigue participando para obtener más reconocimientos!</p>
        </div>
    <?php endif; ?>
</div>

<style>
.insignias-page {
    max-width: 1200px;
    margin: 0 auto;
}

.subtitle {
    text-align: center;
    color: #7f8c8d;
    margin-bottom: 40px;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.empty-icon {
    font-size: 5rem;
    margin-bottom: 20px;
    opacity: 0.3;
}

.insignias-grid-full {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
    margin-bottom: 40px;
}

.insignia-card-full {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transition: all 0.3s;
}

.insignia-card-full:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
}

.insignia-header {
    padding: 40px 20px;
    text-align: center;
}

.insignia-icon-large {
    font-size: 5rem;
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.insignia-body {
    padding: 25px;
}

.insignia-body h3 {
    margin: 0 0 10px 0;
    color: #2c3e50;
    font-size: 1.3rem;
}

.insignia-description {
    color: #7f8c8d;
    font-size: 0.9rem;
    margin-bottom: 20px;
    line-height: 1.5;
}

.insignia-metadata {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.badge-categoria {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: bold;
    color: white;
    text-transform: uppercase;
    align-self: flex-start;
}

.fecha-obtencion,
.convocatoria-vinculada {
    font-size: 0.85rem;
    color: #95a5a6;
}

.insignias-summary {
    text-align: center;
    padding: 30px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px;
    margin-top: 20px;
}

.insignias-summary h3 {
    margin: 0 0 10px 0;
}

.insignias-summary p {
    margin: 0;
    opacity: 0.9;
}

@media (max-width: 768px) {
    .insignias-grid-full {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>