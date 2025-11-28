<?php
// app/Models/Mongo/UsuarioInsignia.php

require_once __DIR__ . '/BaseModel.php';
require_once __DIR__ . '/Insignia.php';

class UsuarioInsignia extends BaseModel {
    public function __construct() {
        parent::__construct('usuario_insignias');
    }
    
    /**
     * Otorgar insignia a usuario
     */
    public function otorgar($usuarioId, $insigniaId) {
        // Verificar si ya tiene la insignia
        $existe = $this->collection->findOne([
            'usuario_id' => (int)$usuarioId,
            'insignia_id' => $this->toObjectId($insigniaId)
        ]);

        if ($existe) {
            return false;
        }

        $datos = [
            'usuario_id' => (int)$usuarioId,
            'insignia_id' => $this->toObjectId($insigniaId),
            'fecha_otorgada' => new MongoDB\BSON\UTCDateTime(),
            'convocatoria_id' => null
        ];

        $datos = $this->withTimestamps($datos);
        $result = $this->collection->insertOne($datos);
        return $result->getInsertedId();
    }
    
    /**
     * Obtener insignias de un usuario 
     */
    public function obtenerPorUsuario($usuarioId) {
        // Obtener todas las relaciones usuario_insignia
        $relaciones = $this->collection->find([
            'usuario_id' => (int)$usuarioId
        ]);
        
        $insignias = [];
        
        foreach ($relaciones as $relacion) {
            // Obtener la insignia usando el modelo existente
            $insigniaModel = new Insignia();
            $insignia = $insigniaModel->obtenerPorId($relacion['insignia_id']);
            
            if ($insignia) {
                $insignias[] = [
                    '_id' => $this->toString($relacion['_id']),
                    'usuario_id' => $relacion['usuario_id'],
                    'insignia_id' => $this->toString($relacion['insignia_id']),
                    'fecha_otorgada' => $relacion['fecha_otorgada'],
                    'convocatoria_id' => $relacion['convocatoria_id'] ?? null,
                    'insignia_info' => $insignia
                ];
            }
        }
        
        return $insignias;
    }
    
    /**
     * Verificar si usuario tiene insignia
     */
    public function tieneInsignia($usuarioId, $insigniaId) {
        $existe = $this->collection->findOne([
            'usuario_id' => (int)$usuarioId,
            'insignia_id' => $this->toObjectId($insigniaId)
        ]);
        
        return $existe !== null;
    }

    public function otorgarConConvocatoria($usuarioId, $insigniaId, $convocatoriaId) {
        $existe = $this->collection->findOne([
            'usuario_id' => (int)$usuarioId,
            'insignia_id' => $this->toObjectId($insigniaId)
        ]);

        if ($existe) {
            return false;
        }

        $datos = [
            'usuario_id' => (int)$usuarioId,
            'insignia_id' => $this->toObjectId($insigniaId),
            'fecha_otorgada' => new MongoDB\BSON\UTCDateTime(),
            'convocatoria_id' => (int)$convocatoriaId
        ];

        $datos = $this->withTimestamps($datos);
        $result = $this->collection->insertOne($datos);
        return $result->getInsertedId();
    }
}