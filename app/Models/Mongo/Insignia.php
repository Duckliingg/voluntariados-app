<?php
// app/Models/Mongo/Insignia.php
require_once __DIR__ . '/BaseModel.php';

class Insignia extends BaseModel {
    
    public function __construct() {
        parent::__construct('insignias');
    }

    /**
     * Crear nueva insignia
     */
    public function crear($datos) {
        try {
            $resultado = $this->collection->insertOne($datos);
            return $this->toString($resultado->getInsertedId());
        } catch (Exception $e) {
            throw new Exception("Error al crear insignia: " . $e->getMessage());
        }
    }

    /**
     * Actualizar insignia
     */
    public function actualizar($id, $datos) {
        try {
            $resultado = $this->collection->updateOne(
                ['_id' => $this->toObjectId($id)],
                ['$set' => $datos]
            );
            return $resultado->getModifiedCount() > 0;
        } catch (Exception $e) {
            throw new Exception("Error al actualizar insignia: " . $e->getMessage());
        }
    }

    /**
     * Eliminar insignia
     */
    public function eliminar($id) {
        try {
            $resultado = $this->collection->deleteOne([
                '_id' => $this->toObjectId($id)
            ]);
            return $resultado->getDeletedCount() > 0;
        } catch (Exception $e) {
            throw new Exception("Error al eliminar insignia: " . $e->getMessage());
        }
    }

    /**
     * Obtener insignia por ID
     */
    public function obtenerPorId($id) {
        try {
            $insignia = $this->collection->findOne([
                '_id' => $this->toObjectId($id)
            ]);
            
            if ($insignia) {
                return $this->formatearInsignia($insignia);
            }
            return null;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Obtener todas las insignias (incluye inactivas)
     */
    public function obtenerTodas() {
        $cursor = $this->collection->find(
            [],
            ['sort' => ['fecha_creacion' => -1]]
        );
        
        $insignias = [];
        foreach ($cursor as $doc) {
            $insignias[] = $this->formatearInsignia($doc);
        }
        return $insignias;
    }

    /**
     * Obtener solo insignias activas
     */
    public function obtenerActivas() {
        $cursor = $this->collection->find(
            ['activa' => true],
            ['sort' => ['categoria' => 1, 'nombre' => 1]]
        );
        
        $insignias = [];
        foreach ($cursor as $doc) {
            $insignias[] = $this->formatearInsignia($doc);
        }
        return $insignias;
    }

    /**
     * Buscar insignias por categoría
     */
    public function buscarPorCategoria($categoria) {
        $cursor = $this->collection->find([
            'categoria' => $categoria,
            'activa' => true
        ]);
        
        $insignias = [];
        foreach ($cursor as $doc) {
            $insignias[] = $this->formatearInsignia($doc);
        }
        return $insignias;
    }

    /**
     * Buscar por tipo (mantener compatibilidad)
     */
    public function buscarPorTipo($tipo) {
        return $this->buscarPorCategoria($tipo);
    }

    /**
     * Buscar insignia por nombre
     */
    public function buscarPorNombre($nombre) {
        $insignia = $this->collection->findOne([
            'nombre' => $nombre
        ]);
        
        if ($insignia) {
            return $this->formatearInsignia($insignia);
        }
        return null;
    }

    /**
     * Buscar por ID (alias para compatibilidad)
     */
    public function buscarPorId($id) {
        return $this->obtenerPorId($id);
    }

    /**
     * Contar insignias por categoría
     */
    public function contarPorCategoria($categoria) {
        return $this->collection->countDocuments([
            'categoria' => $categoria,
            'activa' => true
        ]);
    }

    /**
     * Obtener estadísticas de insignias
     */
    public function obtenerEstadisticas() {
        $pipeline = [
            [
                '$group' => [
                    '_id' => '$categoria',
                    'total' => ['$sum' => 1],
                    'activas' => [
                        '$sum' => [
                            '$cond' => [
                                ['$eq' => ['$activa', true]],
                                1,
                                0
                            ]
                        ]
                    ]
                ]
            ],
            ['$sort' => ['_id' => 1]]
        ];

        $resultado = $this->collection->aggregate($pipeline);
        
        $stats = [];
        foreach ($resultado as $doc) {
            $stats[] = [
                'categoria' => $doc['_id'],
                'total' => $doc['total'],
                'activas' => $doc['activas']
            ];
        }
        return $stats;
    }

    /**
     * Formatear insignia para salida
     */
    private function formatearInsignia($doc) {
        $insignia = (array)$doc;
        $insignia['_id'] = $this->toString($doc['_id']);
        
        // Convertir UTCDateTime a string si existe
        if (isset($insignia['fecha_creacion']) && $insignia['fecha_creacion'] instanceof \MongoDB\BSON\UTCDateTime) {
            $insignia['fecha_creacion'] = $insignia['fecha_creacion']->toDateTime()->format('Y-m-d H:i:s');
        }
        
        // Valores por defecto
        $insignia['icono'] = $insignia['icono'] ?? '🏅';
        $insignia['color'] = $insignia['color'] ?? '#3498db';
        $insignia['activa'] = $insignia['activa'] ?? true;
        $insignia['criterio_asistencia'] = $insignia['criterio_asistencia'] ?? 80;
        
        return $insignia;
    }
}