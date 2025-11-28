<?php
// app/Models/Mongo/Asistencia.php

require_once __DIR__ . '/BaseModel.php';

class Asistencia extends BaseModel {
    public function __construct() {
        parent::__construct('asistencias');
    }
    
    /**
     * Registrar asistencia y otorgar insignias automáticamente
     */
    public function registrar($datos) {
        $datos = $this->withTimestamps($datos);
        $datos['estado'] = 'confirmada';
        $datos['insignias_otorgadas'] = [];
        
        // Insertar asistencia
        $result = $this->collection->insertOne($datos);
        return $this->toString($result->getInsertedId());
    }
    
    /**
     * Obtener asistencias por usuario
     */
    public function obtenerPorUsuario($usuarioId) {
        $cursor = $this->collection->find([
            'usuario_id' => (int)$usuarioId
        ]);
        
        $asistencias = [];
        foreach ($cursor as $doc) {
            $doc['_id'] = $this->toString($doc['_id']);
            $asistencias[] = $doc;
        }
        
        return $asistencias;
    }
    
    /**
     * Obtener asistencias por convocatoria
     */
    public function obtenerPorConvocatoria($convocatoriaId) {
        $cursor = $this->collection->find([
            'convocatoria_id' => (int)$convocatoriaId
        ]);
        
        $asistencias = [];
        foreach ($cursor as $doc) {
            $doc['_id'] = $this->toString($doc['_id']);
            $asistencias[] = $doc;
        }
        
        return $asistencias;
    }

    /**
     * Otorgar insignias automáticamente después de registrar asistencia
     */
    public function registrarConInsignias($datos) {
        $datos = $this->withTimestamps($datos);
        $datos['estado'] = 'confirmada';
        $datos['insignias_otorgadas'] = [];
        
        // Insertar asistencia
        $result = $this->collection->insertOne($datos);
        $asistenciaId = $result->getInsertedId();
        
        // Otorgar insignias
        $this->otorgarInsigniasAutomaticas(
            $datos['usuario_id'], 
            $datos['convocatoria_id']
        );
        
        return $this->toString($asistenciaId);
    }

    /**
     * Lógica para otorgar insignias automáticamente
     */
    private function otorgarInsigniasAutomaticas($usuarioId, $convocatoriaId) {
        
        $usuarioInsigniaModel = new UsuarioInsignia();
        $insigniaModel = new Insignia();
        
        // Obtener tipo de convocatoria
        $tipoConvocatoria = 'social'; 
        
        // Buscar insignias que coincidan con el tipo
        $insigniasCoincidentes = $insigniaModel->buscarPorTipo($tipoConvocatoria);
        
        foreach ($insigniasCoincidentes as $insignia) {
            // Verificar si el usuario ya tiene esta insignia
            if (!$usuarioInsigniaModel->tieneInsignia($usuarioId, $insignia['_id'])) {
                // Otorgar insignia
                $usuarioInsigniaModel->otorgar($usuarioId, $insignia['_id']);
                
                // Registrar en la asistencia qué insignias se otorgaron
                $this->collection->updateOne(
                    ['_id' => $this->toObjectId($asistenciaId)],
                    ['$push' => ['insignias_otorgadas' => $insignia['_id']]]
                );
                
                error_log("Insignia otorgada: {$insignia['nombre']} al usuario $usuarioId");
            }
        }
    }

    /**
     * Contar total de asistencias registradas para un estudiante en una convocatoria
     */
    public function contarTotal($convocatoriaId, $estudianteId) {
        return $this->collection->countDocuments([
            'convocatoria_id' => (int)$convocatoriaId,
            'estudiante_id' => (int)$estudianteId
        ]);
    }

    /**
     * Contar asistencias marcadas como presente
     */
    public function contarPresentes($convocatoriaId, $estudianteId) {
        return $this->collection->countDocuments([
            'convocatoria_id' => (int)$convocatoriaId,
            'estudiante_id' => (int)$estudianteId,
            'presente' => true
        ]);
    }

    /**
     * Obtener porcentaje de asistencia de un estudiante
     */
    public function calcularPorcentaje($convocatoriaId, $estudianteId) {
        $total = $this->contarTotal($convocatoriaId, $estudianteId);
        
        if ($total == 0) {
            return 0;
        }
        
        $presentes = $this->contarPresentes($convocatoriaId, $estudianteId);
        return round(($presentes / $total) * 100, 2);
    }
}