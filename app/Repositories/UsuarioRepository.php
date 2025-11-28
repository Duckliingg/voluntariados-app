<?php
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Models/MySQL/Usuario.php';

class UsuarioRepository {
    private $conexion;
    
    public function __construct() {
        $db = new Conexion();
        $this->conexion = $db->pdo;
    }
    
    public function buscarPorEmail($email, $tipo = null) {
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $params = [$email];
        
        if ($tipo) {
            $sql .= " AND tipo = ?";
            $params[] = $tipo;
        }
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $data ? new Usuario($data) : null;
    }
    
    public function crear($nombre, $email, $password, $tipo = 'estudiante') {
        $sql = "INSERT INTO usuarios (nombre, email, password, tipo) VALUES (?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$nombre, $email, password_hash($password, PASSWORD_DEFAULT), $tipo]);
    }
    
    public function existeEmail($email) {
        $stmt = $this->conexion->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }

    public function obtenerDocentes() {
        $sql = "SELECT id, nombre, email FROM usuarios WHERE tipo = 'docente' ORDER BY nombre";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $sql = "SELECT * FROM usuarios WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $data ? new Usuario($data) : null;
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
                $redirect = '/voluntariados/public/admin/';
                break;
                
            case 'docente':
                $_SESSION['docente_id'] = $usuario->id;
                $_SESSION['docente_nombre'] = $usuario->nombre;
                $redirect = '/voluntariados/public/docente/';
                break;
                
            case 'estudiante':
            default:
                $_SESSION['estudiante_id'] = $usuario->id;
                $_SESSION['estudiante_nombre'] = $usuario->nombre;
                $redirect = '/voluntariados/public/estudiantes/';
                break;
        }
        
        return ['success' => true, 'redirect' => $redirect];
    }
}
