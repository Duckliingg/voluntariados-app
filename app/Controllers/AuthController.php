<?php
// app/Controllers/AuthController.php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Services/AuthService.php';

class AuthController extends BaseController {
    
    private $authService;
    
    public function __construct() {
        $this->authService = new AuthService();
    }
    
    // Login de Admin
    public function loginAdmin() {
        // Si ya está autenticado, redirigir al panel
        if ($this->authService->estaAutenticado('admin')) {
            $this->redirect('index.php');
        }
        
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $resultado = $this->authService->login(
                $_POST['email'],
                $_POST['password'],
                'admin'
            );
            
            if ($resultado['success']) {
                $this->redirect('index.php');
            } else {
                $error = $resultado['error'];
            }
        }
        
        $this->render('auth/login_admin', [
            'pageTitle' => 'Login Administrador',
            'baseUrl' => '../',
            'error' => $error
        ]);
    }
    
    // Login de Estudiante
    public function loginEstudiante() {
        // Si ya está autenticado, redirigir al panel
        if ($this->authService->estaAutenticado('estudiante')) {
            $this->redirect('index.php');
        }
        
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $resultado = $this->authService->login(
                $_POST['email'],
                $_POST['password'],
                'estudiante'
            );
            
            if ($resultado['success']) {
                $this->redirect('index.php');
            } else {
                $error = $resultado['error'];
            }
        }
        
        $this->render('auth/login_estudiante', [
            'pageTitle' => 'Login Estudiante',
            'baseUrl' => '../',
            'error' => $error,
            'success' => $this->getFlashMessage('success')
        ]);
    }
    
    // NUEVO: Login de Docente
    public function loginDocente() {
        // Si ya está autenticado, redirigir al panel
        if ($this->authService->estaAutenticado('docente')) {
            $this->redirect('index.php');
        }
        
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $resultado = $this->authService->login(
                $_POST['email'],
                $_POST['password'],
                'docente'
            );
            
            if ($resultado['success']) {
                $this->redirect('index.php');
            } else {
                $error = $resultado['error'];
            }
        }
        
        $this->render('auth/login_docente', [
            'pageTitle' => 'Login Docente',
            'baseUrl' => '../',
            'error' => $error
        ]);
    }
    
    // Registro de Estudiante
    public function registro() {
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $resultado = $this->authService->registrar(
                $_POST['nombre'],
                $_POST['email'],
                $_POST['password'],
                $_POST['password_confirm'],
                $_POST['tipo']
            );
            
            if ($resultado['success']) {
                $success = $resultado['mensaje'];
            } else {
                $error = $resultado['error'];
            }
        }
        
        $this->render('auth/registro', [
            'pageTitle' => 'Registro de Estudiante',
            'baseUrl' => '',
            'error' => $error,
            'success' => $success,
            'postData' => $_POST ?? []
        ]);
    }
    
    // Logout Admin
    public function logoutAdmin() {
        $this->authService->cerrarSesion('admin');
        $this->redirect('../index.php');
    }
    
    // Logout Estudiante
    public function logoutEstudiante() {
        $this->authService->cerrarSesion('estudiante');
        $this->redirect('../index.php');
    }
    
    // NUEVO: Logout Docente
    public function logoutDocente() {
        $this->authService->cerrarSesion('docente');
        $this->redirect('../index.php');
    }
}