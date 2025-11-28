<?php
require_once __DIR__ . '/../Repositories/UsuarioRepository.php';

class AuthService {
    private $usuarioRepo;
    
    public function __construct() {
        $this->usuarioRepo = new UsuarioRepository();
    }
    
    public function login($email, $password, $tipoRequerido) {
        $email = filter_var(trim($email), FILTER_VALIDATE_EMAIL);
        if (!$email) {
            return ['success' => false, 'error' => 'Email inválido'];
        }
        
        $usuario = $this->usuarioRepo->buscarPorEmail($email, $tipoRequerido);
        
        if (!$usuario) {
            return ['success' => false, 'error' => 'Credenciales incorrectas'];
        }
        
        if (!password_verify($password, $usuario->password)) {
            return ['success' => false, 'error' => 'Credenciales incorrectas'];
        }
        
        session_regenerate_id(true);
        $_SESSION[$tipoRequerido . '_id'] = $usuario->id;
        $_SESSION[$tipoRequerido . '_nombre'] = $usuario->nombre;
        
        return ['success' => true, 'usuario' => $usuario];
    }
    
    public function registrar($nombre, $email, $password, $passwordConfirm, $tipo = 'estudiante') {
        if (empty($nombre) || empty($email) || empty($password)) {
            return ['success' => false, 'error' => 'Todos los campos son obligatorios'];
        }
        
        $email = filter_var(trim($email), FILTER_VALIDATE_EMAIL);
        if (!$email) {
            return ['success' => false, 'error' => 'Email inválido'];
        }
        
        if ($password !== $passwordConfirm) {
            return ['success' => false, 'error' => 'Las contraseñas no coinciden'];
        }
        
        if (strlen($password) < 6) {
            return ['success' => false, 'error' => 'La contraseña debe tener al menos 6 caracteres'];
        }
        
        if ($this->usuarioRepo->existeEmail($email)) {
            return ['success' => false, 'error' => 'El email ya está registrado'];
        }
        
        $creado = $this->usuarioRepo->crear($nombre, $email, $password, $tipo);
        
        if ($creado) {
            $mensaje = $tipo == 'docente' ? 'Registro de docente exitoso' : 'Registro exitoso';
            return ['success' => true, 'mensaje' => $mensaje];
        }
        
        return ['success' => false, 'error' => 'Error al registrar'];
    }
    
    public function estaAutenticado($tipo) {
        return isset($_SESSION[$tipo . '_id']);
    }
    
    public function cerrarSesion($tipo) {
        unset($_SESSION[$tipo . '_id']);
        unset($_SESSION[$tipo . '_nombre']);
        session_destroy();
    }
    
    public function obtenerUsuarioActual() {
        if (isset($_SESSION['admin_id'])) {
            return [
                'id' => $_SESSION['admin_id'],
                'nombre' => $_SESSION['admin_nombre'],
                'tipo' => 'admin'
            ];
        } elseif (isset($_SESSION['docente_id'])) {
            return [
                'id' => $_SESSION['docente_id'],
                'nombre' => $_SESSION['docente_nombre'],
                'tipo' => 'docente'
            ];
        } elseif (isset($_SESSION['estudiante_id'])) {
            return [
                'id' => $_SESSION['estudiante_id'],
                'nombre' => $_SESSION['estudiante_nombre'],
                'tipo' => 'estudiante'
            ];
        }
        
        return null;
    }

    public function loginAuto($email, $password) {
        $email = filter_var(trim($email), FILTER_VALIDATE_EMAIL);
        if (!$email) {
            return ['success' => false, 'error' => 'Email inválido'];
        }
        
        $usuario = $this->usuarioRepo->buscarPorEmail($email);
        
        if (!$usuario) {
            return ['success' => false, 'error' => 'Credenciales incorrectas'];
        }
        
        if (!password_verify($password, $usuario->password)) {
            return ['success' => false, 'error' => 'Credenciales incorrectas'];
        }
        
        session_regenerate_id(true);
        
        switch ($usuario->tipo) {
            case 'admin':
                $_SESSION['admin_id'] = $usuario->id;
                $_SESSION['admin_nombre'] = $usuario->nombre;
                $redirect = '../admin/index.php';
                break;
                
            case 'docente':
                $_SESSION['docente_id'] = $usuario->id;
                $_SESSION['docente_nombre'] = $usuario->nombre;
                $redirect = '../docente/index.php';
                break;
                
            case 'estudiante':
            default:
                $_SESSION['estudiante_id'] = $usuario->id;
                $_SESSION['estudiante_nombre'] = $usuario->nombre;
                $redirect = '../estudiantes/index.php';
                break;
        }
        
        return ['success' => true, 'redirect' => $redirect];
    }
}