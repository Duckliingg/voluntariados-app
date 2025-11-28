<?php
// views/public/index.php
// Variables: $convocatorias
$userType = 'public';
include __DIR__ . '/../layouts/header.php';
?>
<!-- Hero Section con Slider -->
<div class="hero">
    <div class="hero-slider">
        <!-- Slide 1 -->
        <div class="slide active" style="background-image: url('<?php echo $baseUrl; ?>assets/img/banner1.jpg')"></div>
        <!-- Slide 2 -->
        <div class="slide" style="background-image: url('<?php echo $baseUrl; ?>assets/img/banner2.jpg')"></div>
        <!-- Slide 3 -->
        <div class="slide" style="background-image: url('<?php echo $baseUrl; ?>assets/img/banner3.jpg')"></div>
    </div>
    <div class="hero-content">
        <h1>Sistema de Voluntariados</h1>
        <p>Universidad Privada de Tacna</p>
        <div class="hero-buttons">
            <a href="<?php echo $baseUrl; ?>auth/login.php" class="btn btn-primary-white">Acceder como Usuario</a>
            <a href="<?php echo $baseUrl; ?>admin/login.php" class="btn btn-outline-white">Acceder como Admin</a>
        </div>
    </div>
    <!-- Indicadores del Slider -->
    <div class="slider-indicators">
        <div class="indicator active"></div>
        <div class="indicator"></div>
        <div class="indicator"></div>
    </div>
</div>

<h2 class="section-title">Convocatorias Activas</h2>
<?php if(empty($convocatorias)): ?>
    <p class="text-center">No hay convocatorias disponibles en este momento.</p>
<?php else: ?>
    <div class="convocatorias-grid">
        <?php foreach($convocatorias as $conv): ?>
        <div class="convocatoria-card">
            <?php if(!empty($conv->imagen)): ?>
                <img src="<?php echo $baseUrl; ?>uploads/<?php echo htmlspecialchars($conv->imagen); ?>"
                     alt="<?php echo htmlspecialchars($conv->titulo); ?>">
            <?php endif; ?>
            
            <div class="p-20">
                <h3><?php echo htmlspecialchars($conv->titulo); ?></h3>
                <p><?php echo nl2br(htmlspecialchars(substr($conv->descripcion, 0, 150))); ?>...</p>
                
                <div class="m-15-0">
                    <p><strong>Lugar:</strong> <?php echo htmlspecialchars($conv->lugar); ?></p>
                    <p><strong>Responsable:</strong> <?php echo htmlspecialchars($conv->contacto_responsable); ?></p>
                    <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($conv->telefono_contacto); ?></p>
                    <p><strong>WhatsApp:</strong> 
                        <a href="<?php echo htmlspecialchars($conv->whatsapp_grupo); ?>" target="_blank">Unirse</a>
                    </p>
                    <p><strong>Tipo de voluntariado:</strong> <?php echo htmlspecialchars($conv->tipo_voluntariado); ?></p>
                    <p><strong>Inicio:</strong> <?php echo date('d/m/Y', strtotime($conv->fecha_inicio)); ?></p>
                    <p><strong>Fin:</strong> <?php echo date('d/m/Y', strtotime($conv->fecha_fin)); ?></p>
                    <p><strong>Cupos:</strong> <?php echo $conv->cupos; ?></p>
                </div>
                
                <a href="auth/login.php" class="btn btn-full-width">
                    Postularme
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../layouts/footer.php'; ?>