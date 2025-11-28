<?php
require_once __DIR__ . '/../../layouts/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1>Mis Convocatorias</h1>
            
            <?php if (empty($convocatorias)): ?>
                <div class="alert alert-info">
                    No tienes convocatorias asignadas.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Lugar</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($convocatorias as $conv): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($conv->titulo); ?></td>
                                <td><?php echo htmlspecialchars($conv->lugar); ?></td>
                                <td><?php echo $conv->fecha_inicio ? date('d/m/Y', strtotime($conv->fecha_inicio)) : '-'; ?></td>
                                <td>
                                    <span class="badge bg-<?php echo ($conv->estado ?? 'activa') == 'activa' ? 'success' : 'secondary'; ?>">
                                        <?php echo ucfirst($conv->estado ?? 'activa'); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="index.php?action=tomar-asistencia&id=<?php echo $conv->id; ?>" 
                                    class="btn btn-sm btn-success">
                                        Tomar Asistencia
                                    </a>

                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../../layouts/footer.php';
