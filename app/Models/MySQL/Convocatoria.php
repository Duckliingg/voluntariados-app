<?php
class Convocatoria {
    public $id;
    public $titulo;
    public $descripcion;
    public $imagen;
    public $lugar;
    public $contacto_responsable;
    public $telefono_contacto;
    public $whatsapp_grupo;
    public $tipo_voluntariado;
    public $fecha_inicio;
    public $fecha_fin;
    public $cupos;
    public $estado;
    public $admin_id;
    public $docente_id;
    public $insignia_id; 
    public $fecha_creacion;
    public $docente_nombre;
    public $docente_email;

    public function __construct($data = []) {
        if (!empty($data)) {
            $this->id = $data['id'] ?? null;
            $this->titulo = $data['titulo'] ?? '';
            $this->descripcion = $data['descripcion'] ?? '';
            $this->imagen = $data['imagen'] ?? '';
            $this->lugar = $data['lugar'] ?? '';
            $this->contacto_responsable = $data['contacto_responsable'] ?? '';
            $this->telefono_contacto = $data['telefono_contacto'] ?? '';
            $this->whatsapp_grupo = $data['whatsapp_grupo'] ?? '';
            $this->tipo_voluntariado = $data['tipo_voluntariado'] ?? 'social';
            $this->fecha_inicio = $data['fecha_inicio'] ?? null;
            $this->fecha_fin = $data['fecha_fin'] ?? null;
            $this->cupos = $data['cupos'] ?? 0;
            $this->estado = $data['estado'] ?? 'activa';
            $this->admin_id = $data['admin_id'] ?? null;
            $this->docente_id = $data['docente_id'] ?? null;
            $this->insignia_id = $data['insignia_id'] ?? null; 
            $this->fecha_creacion = $data['fecha_creacion'] ?? null;
            $this->docente_nombre = $data['docente_nombre'] ?? '';
            $this->docente_email = $data['docente_email'] ?? '';
        }
    }
}