<?php
// views/admin/insignias/index.php
$userType = 'admin';
include __DIR__ . '/../../layouts/header.php';
?>

<div class="page-header">
    <h2 class="section-title">Gestión de Insignias</h2>
    <a href="crear.php" class="btn">+ Nueva Insignia</a>
</div>

<?php if(isset($success) && $success): ?>
    <div class="mensaje-exito"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if(isset($error) && $error): ?>
    <div class="mensaje-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<?php if(empty($insignias)): ?>
    <div class="alert-info">
        <p>No hay insignias creadas aún.</p>
        <a href="crear.php" class="btn">Crear la primera insignia</a>
    </div>
<?php else: ?>
    <div class="insignias-admin-grid">
        <?php foreach($insignias as $insignia): ?>
        <div class="insignia-admin-card">
            <div class="insignia-admin-header" style="background: <?php echo htmlspecialchars($insignia['color']); ?>">
                <span class="insignia-admin-icon"><?php echo htmlspecialchars($insignia['icono']); ?></span>
                <?php if($insignia['activa']): ?>
                    <span class="badge-activa">Activa</span>
                <?php else: ?>
                    <span class="badge-inactiva">Inactiva</span>
                <?php endif; ?>
            </div>
            
            <div class="insignia-admin-body">
                <h3><?php echo htmlspecialchars($insignia['nombre']); ?></h3>
                <p class="insignia-descripcion"><?php echo htmlspecialchars($insignia['descripcion']); ?></p>
                
                <div class="insignia-details">
                    <span class="insignia-categoria">
                         <?php echo ucfirst(htmlspecialchars($insignia['categoria'])); ?>
                    </span>
                    <span class="insignia-criterio">
                         <?php echo $insignia['criterio_asistencia']; ?>% asistencia
                    </span>
                </div>

                <div class="insignia-actions">
                    <a href="editar.php?id=<?php echo $insignia['_id']; ?>" class="btn-pequeño btn-editar">
                         Editar
                    </a>
                    
                    <form method="POST" action="cambiar_estado.php" style="display: inline;">
                        <input type="hidden" name="id" value="<?php echo $insignia['_id']; ?>">
                        <input type="hidden" name="activa" value="<?php echo $insignia['activa'] ? '0' : '1'; ?>">
                        <button type="submit" class="btn-pequeño <?php echo $insignia['activa'] ? 'btn-warning' : 'btn-success'; ?>">
                            <?php echo $insignia['activa'] ? 'Desactivar' : 'Activar'; ?>
                        </button>
                    </form>
                    
                    <button onclick="confirmarEliminar('<?php echo $insignia['_id']; ?>', '<?php echo htmlspecialchars($insignia['nombre']); ?>')" 
                            class="btn-pequeño btn-danger">
                         Eliminar
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Formulario oculto para eliminar -->
<form id="formEliminar" method="POST" action="eliminar.php" style="display: none;">
    <input type="hidden" name="id" id="eliminarId">
</form>

<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.insignias-admin-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.insignia-admin-card {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}

.insignia-admin-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.insignia-admin-header {
    padding: 30px 20px;
    text-align: center;
    position: relative;
}

.insignia-admin-icon {
    font-size: 3rem;
    display: block;
}

.badge-activa, .badge-inactiva {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: bold;
}

.badge-activa {
    background: rgba(46, 204, 113, 0.2);
    color: #27ae60;
}

.badge-inactiva {
    background: rgba(231, 76, 60, 0.2);
    color: #c0392b;
}

.insignia-admin-body {
    padding: 20px;
}

.insignia-admin-body h3 {
    margin: 0 0 10px 0;
    color: #2c3e50;
    font-size: 1.2rem;
}

.insignia-descripcion {
    color: #7f8c8d;
    font-size: 0.9rem;
    margin-bottom: 15px;
    min-height: 40px;
}

.insignia-details {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 15px;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 4px;
}

.insignia-categoria, .insignia-criterio {
    font-size: 0.85rem;
    color: #495057;
}

.insignia-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.btn-pequeño {
    padding: 6px 12px;
    font-size: 0.85rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: all 0.2s;
}

.btn-editar {
    background: #3498db;
    color: white;
}

.btn-editar:hover {
    background: #2980b9;
}

.btn-warning {
    background: #f39c12;
    color: white;
}

.btn-warning:hover {
    background: #d68910;
}

.btn-success {
    background: #27ae60;
    color: white;
}

.btn-success:hover {
    background: #229954;
}

.btn-danger {
    background: #e74c3c;
    color: white;
}

.btn-danger:hover {
    background: #c0392b;
}

.alert-info {
    background: #e3f2fd;
    border: 1px solid #90caf9;
    border-radius: 8px;
    padding: 30px;
    text-align: center;
}
</style>

<script>
function confirmarEliminar(id, nombre) {
    if(confirm('¿Estás seguro de eliminar la insignia "' + nombre + '"?\n\nEsta acción no se puede deshacer.')) {
        document.getElementById('eliminarId').value = id;
        document.getElementById('formEliminar').submit();
    }
}
</script>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>