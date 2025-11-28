<?php
// views/estudiantes/convocatorias/index.php
// Variables: $convocatorias, $success, $error

$userType = 'estudiante';
include __DIR__ . '/../../layouts/header.php';
?>

<h2 class="section-title">Convocatorias Activas</h2>

<?php if($success): ?>
    <div class="mensaje-exito"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if($error): ?>
    <div class="mensaje-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

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
                <p><?php echo nl2br(htmlspecialchars(substr($conv->descripcion, 0, 150))); ?>...</p>
                
                <div class="convocatoria-info">
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
                
                <a href="postular.php?id=<?php echo $conv->id; ?>" class="btn" class="btn-full-width">
                    Postularme
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>


