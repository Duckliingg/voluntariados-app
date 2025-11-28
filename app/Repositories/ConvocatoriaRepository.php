<?php
require_once __DIR__ . '/../Models/MySQL/Convocatoria.php';

class ConvocatoriaRepository {
    private $conexion;

    public function __construct() {
        $db = new Conexion();
        $this->conexion = $db->pdo;
    }

    public function obtenerTodas() {
        $sql = "SELECT * FROM convocatorias ORDER BY fecha_creacion DESC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $convocatorias = [];
        foreach ($data as $row) {
            $convocatorias[] = new Convocatoria($row);
        }

        return $convocatorias;
    }

    public function obtenerActivas() {
        $sql = "SELECT * FROM convocatorias WHERE estado = 'activa' ORDER BY fecha_creacion DESC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $convocatorias = [];
        foreach ($data as $row) {
            $convocatorias[] = new Convocatoria($row);
        }

        return $convocatorias;
    }

    public function obtenerPorId($id) {
        $sql = "SELECT * FROM convocatorias WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new Convocatoria($data) : null;
    }

    public function obtenerPorDocente($docenteId) {
        $sql = "SELECT * FROM convocatorias WHERE docente_id = ? AND estado = 'activa' ORDER BY fecha_creacion DESC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$docenteId]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $convocatorias = [];
        foreach ($data as $row) {
            $convocatorias[] = new Convocatoria($row);
        }

        return $convocatorias;
    }

    public function crear($datos) {
        $sql = "INSERT INTO convocatorias (
            titulo, descripcion, imagen, lugar, contacto_responsable,
            telefono_contacto, whatsapp_grupo, tipo_voluntariado,
            fecha_inicio, fecha_fin, cupos, estado, admin_id, docente_id, insignia_id
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            $datos['titulo'],
            $datos['descripcion'],
            $datos['imagen'],
            $datos['lugar'],
            $datos['contacto_responsable'],
            $datos['telefono_contacto'],
            $datos['whatsapp_grupo'],
            $datos['tipo_voluntariado'],
            $datos['fecha_inicio'],
            $datos['fecha_fin'],
            $datos['cupos'],
            'activa',
            $datos['admin_id'],
            $datos['docente_id'],
            $datos['insignia_id'] ?? null
        ]);
    }

    public function actualizar($id, $datos) {
        $sql = "UPDATE convocatorias SET
            titulo = ?, descripcion = ?, imagen = ?, lugar = ?,
            contacto_responsable = ?, telefono_contacto = ?,
            whatsapp_grupo = ?, tipo_voluntariado = ?,
            fecha_inicio = ?, fecha_fin = ?, cupos = ?, docente_id = ?, insignia_id = ?
            WHERE id = ?";

        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            $datos['titulo'],
            $datos['descripcion'],
            $datos['imagen'],
            $datos['lugar'],
            $datos['contacto_responsable'],
            $datos['telefono_contacto'],
            $datos['whatsapp_grupo'],
            $datos['tipo_voluntariado'],
            $datos['fecha_inicio'],
            $datos['fecha_fin'],
            $datos['cupos'],
            $datos['docente_id'],
            $datos['insignia_id'] ?? null, 
            $id
        ]);
    }

    public function eliminar($id) {
        $sql = "DELETE FROM convocatorias WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function cambiarEstado($id, $estado) {
        $sql = "UPDATE convocatorias SET estado = ? WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([$estado, $id]);
    }

    public function buscarPorId($id) {
        return $this->obtenerPorId($id);
    }
}