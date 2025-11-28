<?php
// views/auth/login_admin.php
// Variables: $error

$userType = 'public';
include __DIR__ . '/../layouts/header.php';
?>

<h2 class="section-title">Login Administrador</h2>

<?php if(isset($error) && $error): ?>
    <div class="mensaje-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<form method="POST">
    <div class="form-group">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    
    <div class="form-group">
        <label class="form-label">Contraseña</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    
    <button type="submit" class="btn">Ingresar</button>
</form>

<p class="text-center mt-20">
    <a href="<?php echo $baseUrl; ?>index.php">Volver al inicio</a>
</p>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
