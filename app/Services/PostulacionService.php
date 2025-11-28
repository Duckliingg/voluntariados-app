<?php
require_once __DIR__ . '/../Repositories/PostulacionRepository.php';
require_once __DIR__ . '/../Repositories/ConvocatoriaRepository.php';

class PostulacionService {
    private $postulacionRepo;
    private $convocatoriaRepo;
    
    public function __construct() {
        $this->postulacionRepo = new PostulacionRepository();
        $this->convocatoriaRepo = new ConvocatoriaRepository();
    }
    
    public function postular($estudianteId, $convocatoriaId) {
        // Validar que la convocatoria existe
        $convocatoria = $this->convocatoriaRepo->obtenerPorId($convocatoriaId);
        if (!$convocatoria) {
            return ['success' => false, 'error' => 'Convocatoria no encontrada'];
        }
        
        // Validar que la convocatoria está activa
        if ($convocatoria->estado != 'activa') {
            return ['success' => false, 'error' => 'Esta convocatoria ya no está activa'];
        }
        
        // Validar que no haya postulado antes
        if ($this->postulacionRepo->yaPostulo($estudianteId, $convocatoriaId)) {
            return ['success' => false, 'error' => 'Ya te has postulado a esta convocatoria'];
        }
        
        // Validar que hay cupos disponibles
        $aprobadas = $this->postulacionRepo->contarAprobadasPorConvocatoria($convocatoriaId);
        if ($aprobadas >= $convocatoria->cupos) {
            return ['success' => false, 'error' => 'No hay cupos disponibles'];
        }
        
        // Crear postulación
        $creado = $this->postulacionRepo->crear($estudianteId, $convocatoriaId);
        
        if ($creado) {
            return ['success' => true, 'mensaje' => 'Postulación enviada exitosamente'];
        }
        
        return ['success' => false, 'error' => 'Error al enviar la postulación'];
    }
    
    public function aprobar($id) {
        $postulacion = $this->postulacionRepo->obtenerPorId($id);
        if (!$postulacion) {
            return ['success' => false, 'error' => 'Postulación no encontrada'];
        }
        
        // Verificar cupos disponibles
        $convocatoria = $this->convocatoriaRepo->obtenerPorId($postulacion->convocatoria_id);
        $aprobadas = $this->postulacionRepo->contarAprobadasPorConvocatoria($postulacion->convocatoria_id);
        
        if ($aprobadas >= $convocatoria->cupos) {
            return ['success' => false, 'error' => 'No hay cupos disponibles'];
        }
        
        $actualizado = $this->postulacionRepo->cambiarEstado($id, 'aprobada');
        
        if ($actualizado) {
            return ['success' => true, 'mensaje' => 'Postulación aprobada'];
        }
        
        return ['success' => false, 'error' => 'Error al aprobar'];
    }
    
    public function rechazar($id) {
        $postulacion = $this->postulacionRepo->obtenerPorId($id);
        if (!$postulacion) {
            return ['success' => false, 'error' => 'Postulación no encontrada'];
        }
        
        $actualizado = $this->postulacionRepo->cambiarEstado($id, 'rechazada');
        
        if ($actualizado) {
            return ['success' => true, 'mensaje' => 'Postulación rechazada'];
        }
        
        return ['success' => false, 'error' => 'Error al rechazar'];
    }
    
    public function obtenerTodas() {
        return $this->postulacionRepo->obtenerTodas();
    }
    
    public function obtenerPorEstudiante($estudianteId) {
        return $this->postulacionRepo->obtenerPorEstudiante($estudianteId);
    }
}
