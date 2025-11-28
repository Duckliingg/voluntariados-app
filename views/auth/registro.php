<?php
// views/auth/registro.php
// Variables: $error, $success, $postData

$userType = 'public';
include __DIR__ . '/../layouts/header.php';
?>

<h2 class="section-title">Crear Cuenta</h2>

<?php if(isset($success) && $success): ?>
    <div class="mensaje-exito"><?php echo htmlspecialchars($success); ?></div>
    <p class="text-center">
        <a href="<?php echo $baseUrl; ?>auth/login.php">Ir al login</a>
    </p>
<?php endif; ?>

<?php if(isset($error) && $error): ?>
    <div class="mensaje-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<?php if(!isset($success) || !$success): ?>
<form method="POST">
    <div class="form-group">
        <label class="form-label">Nombre Completo</label>
        <input type="text" name="nombre" class="form-control" required 
               value="<?php echo isset($postData['nombre']) ? htmlspecialchars($postData['nombre']) : ''; ?>">
    </div>
    
    <div class="form-group">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required
               value="<?php echo isset($postData['email']) ? htmlspecialchars($postData['email']) : ''; ?>">
    </div>
    
    <div class="form-group">
        <label class="form-label">Tipo de Usuario</label>
        <select name="tipo" class="form-control" required>
            <option value="estudiante" <?php echo (isset($postData['tipo']) && $postData['tipo'] == 'estudiante') ? 'selected' : ''; ?>>Estudiante</option>
            <option value="docente" <?php echo (isset($postData['tipo']) && $postData['tipo'] == 'docente') ? 'selected' : ''; ?>>Docente</option>
        </select>
    </div>
    
    <div class="form-group">
        <label class="form-label">Contraseña</label>
        <input type="password" name="password" class="form-control" required>
        <small>Mínimo 6 caracteres</small>
    </div>
    
    <div class="form-group">
        <label class="form-label">Confirmar Contraseña</label>
        <input type="password" name="password_confirm" class="form-control" required>
    </div>
    
    <button type="submit" class="btn">Registrarse</button>
</form>

<p class="text-center mt-20">
    ¿Ya tienes cuenta? 
    <a href="<?php echo $baseUrl; ?>auth/login.php">Inicia sesión como Estudiante/Docente</a> | 
    <a href="<?php echo $baseUrl; ?>admin/login.php">Inicia sesión como Admin</a><br>
    <a href="<?php echo $baseUrl; ?>index.php">Volver al inicio</a>
</p>
<?php endif; ?>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
