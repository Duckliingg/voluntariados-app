<?php
require_once __DIR__ . '/../Repositories/InsigniaRepository.php';
require_once __DIR__ . '/../Repositories/PostulacionRepository.php';
require_once __DIR__ . '/../Models/Mongo/UsuarioInsignia.php';
require_once __DIR__ . '/../Models/Mongo/Insignia.php';

class InsigniaService
{
    private $postulacionRepo;
    private $insigniaRepo;
    private $usuarioInsigniaModel;
    private $insigniaModel;

    public function __construct()
    {
        $this->postulacionRepo = new PostulacionRepository();
        $this->insigniaRepo = new InsigniaRepository();
        $this->usuarioInsigniaModel = new UsuarioInsignia();
        $this->insigniaModel = new Insignia();
    }

    // ============= NUEVOS MÉTODOS CRUD =============
    
    /**
     * Crear nueva insignia
     */
    public function crear($nombre, $descripcion, $categoria, $icono, $color, $criterioAsistencia) {
        try {
            if (empty($nombre) || empty($categoria)) {
                return ['success' => false, 'error' => 'Nombre y categoría son obligatorios'];
            }

            if ($criterioAsistencia < 0 || $criterioAsistencia > 100) {
                return ['success' => false, 'error' => 'El criterio de asistencia debe estar entre 0 y 100'];
            }

            // Verificar si ya existe una insignia con el mismo nombre
            $existe = $this->insigniaModel->buscarPorNombre($nombre);
            if ($existe) {
                return ['success' => false, 'error' => 'Ya existe una insignia con ese nombre'];
            }

            $insigniaId = $this->insigniaModel->crear([
                'nombre' => trim($nombre),
                'descripcion' => trim($descripcion),
                'categoria' => $categoria,
                'icono' => $icono ?: '🏅',
                'color' => $color ?: '#3498db',
                'criterio_asistencia' => (int)$criterioAsistencia,
                'activa' => true,
                'fecha_creacion' => new \MongoDB\BSON\UTCDateTime()
            ]);

            return ['success' => true, 'id' => $insigniaId];
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Error al crear insignia: ' . $e->getMessage()];
        }
    }

    /**
     * Actualizar insignia existente
     */
    public function actualizar($id, $nombre, $descripcion, $categoria, $icono, $color, $criterioAsistencia, $activa) {
        try {
            if (empty($nombre) || empty($categoria)) {
                return ['success' => false, 'error' => 'Nombre y categoría son obligatorios'];
            }

            $resultado = $this->insigniaModel->actualizar($id, [
                'nombre' => trim($nombre),
                'descripcion' => trim($descripcion),
                'categoria' => $categoria,
                'icono' => $icono ?: '🏅',
                'color' => $color ?: '#3498db',
                'criterio_asistencia' => (int)$criterioAsistencia,
                'activa' => (bool)$activa
            ]);

            if ($resultado) {
                return ['success' => true];
            }
            return ['success' => false, 'error' => 'No se pudo actualizar la insignia'];
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Error al actualizar insignia: ' . $e->getMessage()];
        }
    }

    /**
     * Eliminar insignia
     */
    public function eliminar($id) {
        try {
            // Verificar si la insignia está siendo usada en alguna convocatoria
            // TODO: Implementar verificación en MySQL
            
            $resultado = $this->insigniaModel->eliminar($id);
            
            if ($resultado) {
                return ['success' => true];
            }
            return ['success' => false, 'error' => 'No se pudo eliminar la insignia'];
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Error al eliminar insignia: ' . $e->getMessage()];
        }
    }

    /**
     * Obtener todas las insignias
     */
    public function obtenerTodas() {
        return $this->insigniaModel->obtenerTodas();
    }

    /**
     * Obtener insignias activas (para selector)
     */
    public function obtenerActivas() {
        return $this->insigniaModel->obtenerActivas();
    }

    /**
     * Obtener insignia por ID
     */
    public function obtenerPorId($id) {
        return $this->insigniaModel->obtenerPorId($id);
    }

    /**
     * Cambiar estado activa/inactiva
     */
    public function cambiarEstado($id, $activa) {
        try {
            $resultado = $this->insigniaModel->actualizar($id, ['activa' => (bool)$activa]);
            
            if ($resultado) {
                return ['success' => true];
            }
            return ['success' => false, 'error' => 'No se pudo cambiar el estado'];
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Error: ' . $e->getMessage()];
        }
    }

    // ============= MÉTODOS EXISTENTES =============

    public function evaluarYGuardar(int $estudianteId): void
    {
        $aprobadas = $this->postulacionRepo->obtenerAprobadasPorEstudiante($estudianteId);

        $contador = [
            'ambiental'   => 0,
            'educativo'   => 0,
            'social'      => 0,
            'salud'       => 0,
            'comunitario' => 0,
        ];

        foreach ($aprobadas as $p) {
            $contador[$p['tipo_voluntariado']]++;
        }

        $insignias = [];

        if ($contador['ambiental']  >= 3)  $insignias[] = $this->badge('Reforestador',  'Participó en 3 convocatorias ambientales',  'reforestador.png', 'ambiental');
        if ($contador['educativo']  >= 2)  $insignias[] = $this->badge('Educador',      'Participó en 2 convocatorias educativas',  'educador.png', 'educativo');
        if (array_sum($contador)    >= 10) $insignias[] = $this->badge('Voluntario Oro','Participó en 10 convocatorias',          'voluntario_oro.png', 'general');

        $this->guardarEnMongoDB($estudianteId, $insignias);
        $this->insigniaRepo->save($estudianteId, $insignias);
    }

    private function badge(string $nombre, string $desc, string $img, string $tipo): array
    {
        return [
            'nombre'        => $nombre,
            'descripcion'   => $desc,
            'imagen'        => '/assets/img/badges/' . $img,
            'tipo'          => $tipo,
            'fecha_obtenida'=> new \MongoDB\BSON\UTCDateTime(),
        ];
    }

    private function guardarEnMongoDB(int $estudianteId, array $insignias): void
    {
        foreach ($insignias as $insigniaData) {
            $insigniaExistente = $this->insigniaModel->buscarPorNombre($insigniaData['nombre']);

            if (!$insigniaExistente) {
                $insigniaId = $this->insigniaModel->crear([
                    'nombre' => $insigniaData['nombre'],
                    'descripcion' => $insigniaData['descripcion'],
                    'imagen_url' => $insigniaData['imagen'],
                    'tipo' => $insigniaData['tipo'],
                    'color' => $this->getColorByType($insigniaData['tipo']),
                    'nivel' => 'bronce',
                    'activo' => true
                ]);
            } else {
                $insigniaId = $insigniaExistente['_id'];
            }

            $this->usuarioInsigniaModel->otorgar($estudianteId, $insigniaId);
        }
    }

    private function getColorByType(string $tipo): string
    {
        $colors = [
            'ambiental' => '#27ae60',
            'educativo' => '#3498db',
            'social' => '#e74c3c',
            'salud' => '#9b59b6',
            'comunitario' => '#f39c12',
            'general' => '#95a5a6'
        ];

        return $colors[$tipo] ?? '#95a5a6';
    }

    public function obtenerInsigniasUsuario(int $usuarioId): array
    {
        $insignias = $this->usuarioInsigniaModel->obtenerPorUsuario($usuarioId);
        
        // Formatear las insignias para la vista
        $insigniasFormateadas = [];
        foreach ($insignias as $insignia) {
            $insigniasFormateadas[] = [
                'id' => $insignia['_id'],
                'nombre' => $insignia['insignia_info']['nombre'] ?? 'Sin nombre',
                'descripcion' => $insignia['insignia_info']['descripcion'] ?? '',
                'icono' => $insignia['insignia_info']['icono'] ?? '🏅',
                'color' => $insignia['insignia_info']['color'] ?? '#3498db',
                'categoria' => $insignia['insignia_info']['categoria'] ?? 'general',
                'nivel' => $insignia['insignia_info']['nivel'] ?? 'bronce',
                'fecha_obtenida' => $insignia['fecha_otorgada']->toDateTime()->format('Y-m-d H:i:s'),
                'convocatoria_id' => $insignia['convocatoria_id'] ?? null
            ];
        }
        
        return $insigniasFormateadas;
    }

    public function contarInsigniasUsuario(int $usuarioId): int
    {
        $insignias = $this->obtenerInsigniasUsuario($usuarioId);
        return count($insignias);
    }

    public function otorgarInsigniasPorAsistencia(int $usuarioId, string $tipoConvocatoria, int $convocatoriaId = null): array
    {
        $insigniasOtorgadas = [];
        $insigniasCoincidentes = $this->insigniaModel->buscarPorTipo($tipoConvocatoria);

        foreach ($insigniasCoincidentes as $insignia) {
            if (!$this->usuarioInsigniaModel->tieneInsignia($usuarioId, $insignia['_id'])) {
                // USAR el nuevo método con convocatoria_id
                if ($convocatoriaId) {
                    $this->usuarioInsigniaModel->otorgarConConvocatoria($usuarioId, $insignia['_id'], $convocatoriaId);
                } else {
                    $this->usuarioInsigniaModel->otorgar($usuarioId, $insignia['_id']);
                }
                $insigniasOtorgadas[] = $insignia;
            }
        }

        return $insigniasOtorgadas;
    }
}