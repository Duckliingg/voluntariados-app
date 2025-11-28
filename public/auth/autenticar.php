<?php
// public/auth/autenticar.php

require_once __DIR__ . '/../../app/Services/AuthService.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $authService = new AuthService();
    
    // Login automático (detecta tipo de usuario)
    $resultado = $authService->loginAuto(
        $_POST['email'],
        $_POST['password']
    );
    
    if ($resultado['success']) {
        header('Location: ' . $resultado['redirect']);
        exit;
    } else {
        header('Location: login.php?error=' . urlencode($resultado['error']));
        exit;
    }
}

header('Location: login.php');