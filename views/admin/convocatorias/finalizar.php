<?php
// views/admin/convocatorias/finalizar.php
$userType = 'admin';
include __DIR__ . '/../../layouts/header.php';
?>

<div class="confirmation-container">
    <div class="confirmation-card">
        <div class="confirmation-icon warning">
            ⚠️
        </div>
        
        <h2 class="confirmation-title">Finalizar Convocatoria</h2>
        
        <div class="convocatoria-info">
            <h3><?php echo htmlspecialchars($convocatoria->titulo); ?></h3>
            <div class="info-grid">
                <div class="info-item">
                    <span class="label">Fecha Inicio:</span>
                    <span class="value"><?php echo date('d/m/Y', strtotime($convocatoria->fecha_inicio)); ?></span>
                </div>
                <div class="info-item">
                    <span class="label">Fecha Fin:</span>
                    <span class="value"><?php echo date('d/m/Y', strtotime($convocatoria->fecha_fin)); ?></span>
                </div>
                <div class="info-item">
                    <span class="label">Estado:</span>
                    <span class="value estado-<?php echo $convocatoria->estado; ?>">
                        <?php echo ucfirst($convocatoria->estado); ?>
                    </span>
                </div>
                <?php if (!empty($convocatoria->insignia_id)): ?>
                <div class="info-item insignia-asignada">
                    <span class="label">Insignia Asignada:</span>
                    <span class="value">✓ Sí (se otorgará automáticamente)</span>
                </div>
                <?php else: ?>
                <div class="info-item">
                    <span class="label">Insignia:</span>
                    <span class="value text-muted">No asignada</span>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="alert-warning">
            <strong>¿Qué sucederá al finalizar?</strong>
            <ul>
                <li>La convocatoria cambiará a estado <strong>Inactiva</strong></li>
                <li>Se calcularán las asistencias de todos los estudiantes aprobados</li>
                <li>Los estudiantes que cumplan el criterio de asistencia recibirán su insignia</li>
                <li>Esta acción <strong>no se puede deshacer</strong></li>
            </ul>
        </div>

        <?php if (empty($convocatoria->insignia_id)): ?>
        <div class="alert-info">
            <strong>Nota:</strong> Esta convocatoria no tiene una insignia asignada, por lo que no se otorgarán insignias al finalizar.
        </div>
        <?php endif; ?>

        <form method="POST" class="confirmation-actions">
            <button type="submit" class="btn btn-danger">
                ✓ Sí, Finalizar Convocatoria
            </button>
            <a href="index.php" class="btn btn-secondary">
                ✗ Cancelar
            </a>
        </form>
    </div>
</div>

<style>
.confirmation-container {
    max-width: 700px;
    margin: 40px auto;
}

.confirmation-card {
    background: white;
    border-radius: 12px;
    padding: 40px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    text-align: center;
}

.confirmation-icon {
    font-size: 4rem;
    margin-bottom: 20px;
}

.confirmation-icon.warning {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.confirmation-title {
    color: #2c3e50;
    margin-bottom: 30px;
}

.convocatoria-info {
    background: #f8f9fa;
    padding: 25px;
    border-radius: 8px;
    margin-bottom: 25px;
    text-align: left;
}

.convocatoria-info h3 {
    margin: 0 0 20px 0;
    color: #2c3e50;
    text-align: center;
}

.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.info-item.insignia-asignada {
    grid-column: 1 / -1;
    background: #d4edda;
    padding: 10px;
    border-radius: 6px;
}

.info-item .label {
    font-size: 0.85rem;
    color: #7f8c8d;
    font-weight: 600;
}

.info-item .value {
    font-size: 1rem;
    color: #2c3e50;
}

.estado-activa {
    color: #27ae60;
    font-weight: bold;
}

.estado-inactiva {
    color: #95a5a6;
}

.text-muted {
    color: #95a5a6;
}

.alert-warning {
    background: #fff3cd;
    border: 2px solid #ffc107;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    text-align: left;
}

.alert-warning strong {
    display: block;
    margin-bottom: 10px;
    color: #856404;
}

.alert-warning ul {
    margin: 10px 0 0 20px;
    color: #856404;
}

.alert-warning li {
    margin-bottom: 8px;
}

.alert-info {
    background: #d1ecf1;
    border: 2px solid #17a2b8;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
    text-align: left;
    color: #0c5460;
}

.confirmation-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-top: 30px;
}

.btn {
    padding: 12px 30px;
    border: none;
    border-radius: 6px;
    font-size: 1rem;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s;
    font-weight: 600;
}

.btn-danger {
    background: #e74c3c;
    color: white;
}

.btn-danger:hover {
    background: #c0392b;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(231, 76, 60, 0.3);
}

.btn-secondary {
    background: #95a5a6;
    color: white;
}

.btn-secondary:hover {
    background: #7f8c8d;
}

@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .confirmation-actions {
        flex-direction: column;
    }
}
</style>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>