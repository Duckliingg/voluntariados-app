<?php
// views/estudiantes/convocatorias/postular.php
// Variables: $convocatoria, $error

$userType = 'estudiante';
include __DIR__ . '/../../layouts/header.php';
?>

<h2 class="section-title">Confirmar Postulación</h2>

<?php if(isset($error) && $error): ?>
    <div class="mensaje-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="convocatoria-detail-box">
    <h3><?php echo htmlspecialchars($convocatoria->titulo); ?></h3>
    
    <?php if(!empty($convocatoria->imagen)): ?>
        <img src="<?php echo $baseUrl; ?>uploads/<?php echo htmlspecialchars($convocatoria->imagen); ?>" 
             alt="Imagen" class="convocatoria-detail-img-full">
    <?php endif; ?>
    
    <p><?php echo nl2br(htmlspecialchars($convocatoria->descripcion)); ?></p>
    
    <p><strong>Lugar:</strong> <?php echo htmlspecialchars($convocatoria->lugar); ?></p>
    <p><strong>Responsable:</strong> <?php echo htmlspecialchars($convocatoria->contacto_responsable); ?></p>
    <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($convocatoria->telefono_contacto); ?></p>
    <p><strong>WhatsApp:</strong> 
        <a href="<?php echo htmlspecialchars($convocatoria->whatsapp_grupo); ?>" target="_blank">Unirse</a>
    </p>
    <p><strong>Tipo de voluntariado:</strong> <?php echo htmlspecialchars($convocatoria->tipo_voluntariado); ?></p>
    <p><strong>Fecha inicio:</strong> <?php echo $convocatoria->fecha_inicio; ?></p>
    <p><strong>Fecha fin:</strong> <?php echo $convocatoria->fecha_fin; ?></p>
    <p><strong>Cupos disponibles:</strong> <?php echo $convocatoria->cupos; ?></p>
</div>


<p><strong>¿Estás seguro de postularte a esta convocatoria?</strong></p>

<form method="POST" class="mt-20">
    <button type="submit" class="btn">Sí, Postularme</button>
    <a href="index.php" class="btn" class="btn-secondary">Cancelar</a>
</form>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>