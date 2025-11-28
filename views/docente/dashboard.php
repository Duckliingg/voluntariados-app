<?php
$userType = 'docente';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1>Panel Docente</h1>
            <p>Bienvenido, <?php echo $_SESSION['docente_nombre']; ?></p>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Convocatorias Asignadas</h5>
                    <p class="card-text">Gestiona las convocatorias que tienes asignadas</p>
                    <a href="index.php?action=convocatorias" class="btn btn-primary">Ver Convocatorias</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Tomar Asistencia</h5>
                    <p class="card-text">Registra la asistencia de los estudiantes</p>
                    <a href="index.php?action=convocatorias" class="btn btn-success">Tomar Asistencia</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Reportes</h5>
                    <p class="card-text">Genera reportes de actividades</p>
                    <a href="reportes.php" class="btn btn-info">Ver Reportes</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>
