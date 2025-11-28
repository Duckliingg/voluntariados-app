<?php
class Postulacion {
    public $id;
    public $estudiante_id;
    public $convocatoria_id;
    public $estado;
    public $fecha_postulacion;
    public $estudiante_nombre;
    public $estudiante_email;
    public $convocatoria_titulo;
    
    public function __construct($data = []) {
        if (!empty($data)) {
            $this->id = $data['id'] ?? null;
            $this->estudiante_id = $data['estudiante_id'] ?? null;
            $this->convocatoria_id = $data['convocatoria_id'] ?? null;
            $this->estado = $data['estado'] ?? 'pendiente';
            $this->fecha_postulacion = $data['fecha_postulacion'] ?? null;
            $this->estudiante_nombre = $data['estudiante_nombre'] ?? '';
            $this->estudiante_email = $data['estudiante_email'] ?? '';
            $this->convocatoria_titulo = $data['convocatoria_titulo'] ?? '';
        }
    }
}
