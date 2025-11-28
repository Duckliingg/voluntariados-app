<?php
// public/auth/login.php
session_start();

// Si ya está autenticado, redirigir según tipo
if (isset($_SESSION['estudiante_id'])) {
    header('Location: ../estudiantes/index.php');
    exit;
}
if (isset($_SESSION['docente_id'])) {
    header('Location: ../docente/index.php');
    exit;
}
if (isset($_SESSION['admin_id'])) {
    header('Location: ../admin/index.php');
    exit;
}

require_once __DIR__ . '/../../config/config.php';

$error = isset($_GET['error']) ? $_GET['error'] : null;
$success = isset($_GET['success']) ? $_GET['success'] : null;

// Variables para la vista
$pageTitle = 'Iniciar Sesión';
$userType = 'public';

include __DIR__ . '/../../views/layouts/header.php';
?>

<h2 class="section-title">Iniciar Sesión</h2>
<p class="text-center">Estudiantes y Docentes</p>

<?php if($success): ?>
    <div class="mensaje-exito"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if($error): ?>
    <div class="mensaje-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<form method="POST" action="autenticar.php">
    <div class="form-group">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required
               placeholder="tucorreo@example.com"
               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
    </div>
    
    <div class="form-group">
        <label class="form-label">Contraseña</label>
        <input type="password" name="password" class="form-control" required
               placeholder="Tu contraseña">
    </div>
    
    <button type="submit" class="btn">Ingresar</button>
</form>

<p class="text-center mt-20">
    ¿No tienes cuenta? <a href="<?php echo $baseUrl; ?>registro.php">Regístrate aquí</a><br>
    <a href="<?php echo $baseUrl; ?>admin/login.php">Acceso para Administradores</a><br>
    <a href="<?php echo $baseUrl; ?>index.php">Volver al inicio</a>
</p>

<?php include __DIR__ . '/../../views/layouts/footer.php'; ?>