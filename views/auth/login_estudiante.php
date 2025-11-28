<?php
// views/auth/login.php - LOGIN UNIFICADO
// Variables: $error, $success

$pageTitle = 'Iniciar Sesión';
$userType = 'public';
include __DIR__ . '/../layouts/header.php';
?>

<h2 class="section-title">Iniciar Sesión</h2>
<p class="text-center">Estudiantes y Docentes</p>

<?php if(isset($success) && $success): ?>
    <div class="mensaje-exito"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if(isset($error) && $error): ?>
    <div class="mensaje-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<form method="POST" action="autenticar.php">
    <div class="form-group">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required
               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
    </div>
    
    <div class="form-group">
        <label class="form-label">Contraseña</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    
    <button type="submit" class="btn">Ingresar</button>
</form>

<p class="text-center mt-20">
    ¿No tienes cuenta? <a href="<?php echo $baseUrl; ?>registro.php">Regístrate aquí</a><br>
    <a href="<?php echo $baseUrl; ?>auth/login_admin.php">Acceso para Administradores</a><br>
    <a href="<?php echo $baseUrl; ?>index.php">Volver al inicio</a>
</p>

<?php include __DIR__ . '/../layouts/footer.php'; ?>