<?php
// views/admin/convocatorias/eliminar.php
// Variables: $convocatoria

$userType = 'admin';
include __DIR__ . '/../../layouts/header.php';
?>

<h2 class="section-title">Confirmar Eliminación</h2>

<div class="mensaje-error">
    <strong>¿Está seguro de eliminar esta convocatoria?</strong>
    <p>Esta acción no se puede deshacer.</p>
</div>

<div class="convocatoria-detail-box">
    <h3><?php echo htmlspecialchars($convocatoria->titulo); ?></h3>
    <p><?php echo htmlspecialchars(substr($convocatoria->descripcion, 0, 200)); ?>...</p>
    <p><strong>Fecha inicio:</strong> <?php echo $convocatoria->fecha_inicio; ?></p>
    <p><strong>Fecha fin:</strong> <?php echo $convocatoria->fecha_fin; ?></p>
    <p><strong>Cupos:</strong> <?php echo $convocatoria->cupos; ?></p>
    
    <?php if(!empty($convocatoria->imagen)): ?>
        <img src="<?php echo $baseUrl; ?>uploads/<?php echo htmlspecialchars($convocatoria->imagen); ?>" 
             alt="Imagen" class="convocatoria-detail-img">
    <?php endif; ?>
</div>

<form method="POST"
      action="/voluntariados/public/admin/convocatorias/eliminar.php?id=<?php echo $convocatoria->id; ?>"
      onsubmit="return confirm('¿Seguro que deseas eliminar esta convocatoria?');">
    <button type="submit" class="btn btn-danger">Sí, Eliminar</button>
</form>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>


