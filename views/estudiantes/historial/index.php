<?php
// views/estudiantes/historial/index.php
// Variables: $postulaciones, $success, $error

$userType = 'estudiante';
include __DIR__ . '/../../layouts/header.php';
?>

<h2 class="section-title">Mi Historial de Postulaciones</h2>

<?php if($success): ?>
    <div class="mensaje-exito"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if($error): ?>
    <div class="mensaje-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<?php if(empty($postulaciones)): ?>
    <p>Aún no has realizado ninguna postulación.</p>
    <a href="../convocatorias/index.php" class="btn">Ver Convocatorias Disponibles</a>
<?php else: ?>
    <table class="tabla">
        <thead>
            <tr>
                <th>Convocatoria</th>
                <th>Fecha Postulación</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($postulaciones as $post): ?>
            <tr>
                <td><?php echo htmlspecialchars($post->convocatoria_titulo); ?></td>
                <td><?php echo $post->fecha_postulacion; ?></td>
                <td><?php echo $post->fecha_inicio ?? '-'; ?></td>
                <td><?php echo $post->fecha_fin ?? '-'; ?></td>
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

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
