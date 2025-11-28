<?php
// views/admin/postulaciones/index.php
// Variables: $postulaciones, $success, $error

$userType = 'admin';
include __DIR__ . '/../../layouts/header.php';
?>

<h2 class="section-title">Postulaciones</h2>

<?php if($success): ?>
    <div class="mensaje-exito"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if($error): ?>
    <div class="mensaje-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<?php if(empty($postulaciones)): ?>
    <p>No hay postulaciones registradas.</p>
<?php else: ?>
    <table class="tabla">
        <thead>
            <tr>
                <th>Estudiante</th>
                <th>Email</th>
                <th>Convocatoria</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($postulaciones as $post): ?>
            <tr>
                <td><?php echo htmlspecialchars($post->estudiante_nombre); ?></td>
                <td><?php echo htmlspecialchars($post->estudiante_email); ?></td>
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
                <td>
                    <?php if($post->estado == 'pendiente'): ?>
                        <a href="aprobar.php?id=<?php echo $post->id; ?>" class="btn-pequeño" class="btn-success-sm">Aprobar</a>
                        <a href="rechazar.php?id=<?php echo $post->id; ?>" class="btn-pequeño btn-danger">Rechazar</a>
                    <?php else: ?>
                        <span class="text-muted">-</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>

