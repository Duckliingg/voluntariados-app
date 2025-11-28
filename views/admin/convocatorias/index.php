<?php
// views/admin/convocatorias/index.php
// Variables: $convocatorias, $success, $error

$userType = 'admin';
include __DIR__ . '/../../layouts/header.php';
?>

<div class="flex-between">
    <h2 class="section-title">Convocatorias</h2>
    <a href="crear.php" class="btn">Nueva Convocatoria</a>
</div>

<?php if($success): ?>
    <div class="mensaje-exito"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if($error): ?>
    <div class="mensaje-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<?php if(empty($convocatorias)): ?>
    <p>No hay convocatorias activas.</p>
<?php else: ?>
    <table class="tabla">
        <thead>
            <tr>
                <th>Título</th>
                <th>Docente Asignado</th> 
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Cupos</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($convocatorias as $conv): ?>
            <tr>
                <td><?php echo htmlspecialchars($conv->titulo); ?></td>
                <td>
                    <?php if (!empty($conv->docente_id)): ?>
                        <?php 
                        // Mostrar nombre del docente si está disponible
                        if (isset($conv->docente_nombre)) {
                            echo htmlspecialchars($conv->docente_nombre);
                        } else {
                            echo "Docente ID: " . $conv->docente_id;
                        }
                        ?>
                    <?php else: ?>
                        <span class="text-muted">No asignado</span>
                    <?php endif; ?>
                </td>
                <td><?php echo $conv->fecha_inicio; ?></td>
                <td><?php echo $conv->fecha_fin; ?></td>
                <td><?php echo $conv->cupos; ?></td>
                <td>
                    <span class="<?php echo $conv->estado == 'activa' ? 'estado-activa' : 'estado-inactiva'; ?>">
                        <?php echo ucfirst($conv->estado); ?>
                    </span>
                </td>
                <td>
                    <a href="editar.php?id=<?php echo $conv->id; ?>" class="btn-pequeño">Editar</a>
                    
                    <?php 
                    $hoy = date('Y-m-d');
                    // Permitir finalizar el mismo día o después
                    $puedeFinalizarse = ($conv->estado == 'activa' && $conv->fecha_fin <= $hoy);
                    ?>
                    
                    <?php if($puedeFinalizarse): ?>
                        <a href="finalizar.php?id=<?php echo $conv->id; ?>" 
                           class="btn-pequeño btn-primary"
                           style="background: #9b59b6;">
                            🏆 Finalizar
                        </a>
                    <?php endif; ?>
                    
                    <?php if($conv->estado == 'activa'): ?>
                        <a href="cambiar_estado.php?id=<?php echo $conv->id; ?>&estado=inactiva"
                           class="btn-pequeño btn-warning">
                            Desactivar
                        </a>
                    <?php else: ?>
                        <a href="cambiar_estado.php?id=<?php echo $conv->id; ?>&estado=activa"
                           class="btn-pequeño btn-success">
                            Activar
                        </a>
                    <?php endif; ?>
                    
                    <a href="eliminar.php?id=<?php echo $conv->id; ?>" class="btn-pequeño btn-danger">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>