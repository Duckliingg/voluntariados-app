<?php
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Models/MySQL/Postulacion.php';

class PostulacionRepository {
    private $conexion;
    
    public function __construct() {
        $db = new Conexion();
        $this->conexion = $db->pdo;
    }
    
    public function obtenerTodas() {
        $sql = "SELECT p.*, 
                        u.nombre as estudiante_nombre, 
                        u.email as estudiante_email,
                        c.titulo as convocatoria_titulo
                 FROM postulaciones p
                 INNER JOIN usuarios u ON p.estudiante_id = u.id
                 INNER JOIN convocatorias c ON p.convocatoria_id = c.id
                 ORDER BY p.id DESC";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $postulaciones = [];
        foreach ($resultados as $data) {
            $postulaciones[] = new Postulacion($data);
        }
        return $postulaciones;
    }
    
    public function obtenerPorEstudiante($estudianteId) {
        $sql = "SELECT p.*, 
                        c.titulo as convocatoria_titulo,
                        c.descripcion as convocatoria_descripcion,
                        c.fecha_inicio,
                        c.fecha_fin
                 FROM postulaciones p
                 INNER JOIN convocatorias c ON p.convocatoria_id = c.id
                 WHERE p.estudiante_id = ?
                 ORDER BY p.id DESC";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$estudianteId]);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $postulaciones = [];
        foreach ($resultados as $data) {
            $postulaciones[] = new Postulacion($data);
        }
        return $postulaciones;
    }
    
    public function obtenerPorId($id) {
        $sql = "SELECT p.*, 
                        u.nombre as estudiante_nombre, 
                        u.email as estudiante_email,
                        c.titulo as convocatoria_titulo
                 FROM postulaciones p
                 INNER JOIN usuarios u ON p.estudiante_id = u.id
                 INNER JOIN convocatorias c ON p.convocatoria_id = c.id
                 WHERE p.id = ?";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $data ? new Postulacion($data) : null;
    }
    
    /**
     * Obtener estudiantes aprobados para una convocatoria
     * Este es el método que necesita el docente para tomar asistencia
     */
    public function obtenerEstudiantesAprobados($convocatoriaId) {
        $sql = "SELECT u.id, u.nombre, u.email, p.id as postulacion_id
                FROM postulaciones p
                INNER JOIN usuarios u ON p.estudiante_id = u.id
                WHERE p.convocatoria_id = ? AND p.estado = 'aprobada'
                ORDER BY u.nombre ASC";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$convocatoriaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function crear($estudianteId, $convocatoriaId) {
        $sql = "INSERT INTO postulaciones (estudiante_id, convocatoria_id, estado) 
                VALUES (?, ?, 'pendiente')";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$estudianteId, $convocatoriaId]);
    }
    
    public function yaPostulo($estudianteId, $convocatoriaId) {
        $stmt = $this->conexion->prepare("SELECT COUNT(*) FROM postulaciones WHERE estudiante_id = ? AND convocatoria_id = ?");
        $stmt->execute([$estudianteId, $convocatoriaId]);
        return $stmt->fetchColumn() > 0;
    }
    
    public function cambiarEstado($id, $estado) {
        $stmt = $this->conexion->prepare("UPDATE postulaciones SET estado = ? WHERE id = ?");
        return $stmt->execute([$estado, $id]);
    }
    
    public function contarPorConvocatoria($convocatoriaId) {
        $stmt = $this->conexion->prepare("SELECT COUNT(*) FROM postulaciones WHERE convocatoria_id = ?");
        $stmt->execute([$convocatoriaId]);
        return $stmt->fetchColumn();
    }
    
    public function contarAprobadasPorConvocatoria($convocatoriaId) {
        $stmt = $this->conexion->prepare("SELECT COUNT(*) FROM postulaciones WHERE convocatoria_id = ? AND estado = 'aprobada'");
        $stmt->execute([$convocatoriaId]);
        return $stmt->fetchColumn();
    }

    /**
     * Devuelve las postulaciones APROBADAS de un estudiante
     * con el tipo de voluntariado de cada convocatoria.
     */
    public function obtenerAprobadasPorEstudiante(int $estId): array
    {
        $sql = "SELECT c.tipo_voluntariado
                FROM postulaciones p
                JOIN convocatorias c ON c.id = p.convocatoria_id
                WHERE p.estudiante_id = :est AND p.estado = 'aprobada'";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute(['est' => $estId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener postulaciones aprobadas de una convocatoria
     * Retorna objetos Postulacion completos
     */
    public function obtenerAprobadasPorConvocatoria($convocatoriaId) {
        $sql = "SELECT p.*,
                        u.nombre as estudiante_nombre,
                        u.email as estudiante_email,
                        c.titulo as convocatoria_titulo
                 FROM postulaciones p
                 INNER JOIN usuarios u ON p.estudiante_id = u.id
                 INNER JOIN convocatorias c ON p.convocatoria_id = c.id
                 WHERE p.convocatoria_id = ? AND p.estado = 'aprobada'
                 ORDER BY u.nombre ASC";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$convocatoriaId]);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $postulaciones = [];
        foreach ($resultados as $data) {
            $postulaciones[] = new Postulacion($data);
        }
        return $postulaciones;
    }
}