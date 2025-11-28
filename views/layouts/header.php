<?php
// views/layouts/header.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'OBUN - Voluntariados'; ?></title>
    <link rel="stylesheet" href="/voluntariados/public/assets/css/estilo.css">
</head>
<body>
    <header>
        <div class="container header-content">
            <div class="logo">
                <div class="logo-icon">
                    <img src="/voluntariados/public/assets/img/logo-upt.png" alt="Logo UPT">
                </div>
                <h1><?php echo $pageTitle ?? 'Sistema de Voluntariados'; ?></h1>
            </div>
            <nav>
                <ul>
                    <?php if(isset($userType) && $userType == 'admin'): ?>
                        <li><a href="/voluntariados/public/admin/">Panel Admin</a></li>
                        <li><a href="/voluntariados/public/admin/convocatorias/">Convocatorias</a></li>
                        <li><a href="/voluntariados/public/admin/postulaciones/">Postulaciones</a></li>
                        <li><a href="/voluntariados/public/admin/insignias/">Insignias</a></li>
                        <li><a href="/voluntariados/public/admin/logout.php">Cerrar Sesión</a></li>
                    <?php elseif(isset($userType) && $userType == 'docente'): ?>
                        <li><a href="/voluntariados/public/docente/">Panel Docente</a></li>
                        <li><a href="/voluntariados/public/docente/index.php?action=convocatorias">Mis Convocatorias</a></li>
                        <li><a href="/voluntariados/public/docente/logout.php">Cerrar Sesión</a></li>
                    <?php elseif(isset($userType) && $userType == 'estudiante'): ?>
                        <li><a href="/voluntariados/public/estudiantes/">Panel</a></li>
                        <li><a href="/voluntariados/public/estudiantes/convocatorias/">Convocatorias</a></li>
                        <li><a href="/voluntariados/public/estudiantes/historial/">Mi Historial</a></li>
                        <li><a href="/voluntariados/public/estudiantes/insignias/misInsignias.php">Mis Insignias</a></li>
                        <li><a href="/voluntariados/public/estudiantes/logout.php">Cerrar Sesión</a></li>
                    <?php else: ?>
                        <li><a href="/voluntariados/public/">Inicio</a></li>
                        <li><a href="/voluntariados/public/auth/login.php">Login Estudiante/Docente</a></li>
                        <li><a href="/voluntariados/public/admin/login.php">Login Admin</a></li>
                        <li><a href="/voluntariados/public/registro.php">Registrarse</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <div class="container">
        <div class="dashboard">
