<?php
require_once __DIR__ . '/../Repositories/ConvocatoriaRepository.php';
require_once __DIR__ . '/../Repositories/UsuarioRepository.php'; 

class ConvocatoriaService {
    private $convocatoriaRepo;
    private $usuarioRepo; 
    
    public function __construct() {
        $this->convocatoriaRepo = new ConvocatoriaRepository();
        $this->usuarioRepo = new UsuarioRepository(); 
    }
    
    public function obtenerActivas() {
        $convocatorias = $this->convocatoriaRepo->obtenerActivas();
        return $this->cargarDatosDocente($convocatorias);
    }
    
    public function obtenerTodas() {
        $convocatorias = $this->convocatoriaRepo->obtenerTodas();
        return $this->cargarDatosDocente($convocatorias); 
    }
    
    public function obtenerPorId($id) {
        if (!is_numeric($id) || $id <= 0) {
            return null;
        }
        $convocatoria = $this->convocatoriaRepo->obtenerPorId($id);
        return $this->cargarDatosDocenteIndividual($convocatoria); 
    }
    
    // Cargar datos del docente para múltiples convocatorias
    private function cargarDatosDocente($convocatorias) {
        if (empty($convocatorias)) {
            return $convocatorias;
        }
        
        foreach ($convocatorias as $convocatoria) {
            $this->cargarDatosDocenteIndividual($convocatoria);
        }
        
        return $convocatorias;
    }
    
    //Cargar datos del docente para una convocatoria individual
    private function cargarDatosDocenteIndividual($convocatoria) {
        if ($convocatoria && $convocatoria->docente_id) {
            $docente = $this->usuarioRepo->buscarPorId($convocatoria->docente_id);
            if ($docente) {
                $convocatoria->docente_nombre = $docente->nombre;
                $convocatoria->docente_email = $docente->email;
            } else {
                $convocatoria->docente_nombre = 'Docente no encontrado';
                $convocatoria->docente_email = '';
            }
        }
        
        return $convocatoria;
    }
    
    public function crear($datos, $archivo = null, $adminId = null) {
        // Validaciones básicas
        if (empty($datos['titulo']) || empty($datos['descripcion'])) {
            return ['success' => false, 'error' => 'Título y descripción son obligatorios'];
        }
        
        // Valores por defecto para campos opcionales
        $datos['lugar'] = $datos['lugar'] ?? '';
        $datos['contacto_responsable'] = $datos['contacto_responsable'] ?? '';
        $datos['telefono_contacto'] = $datos['telefono_contacto'] ?? '';
        $datos['whatsapp_grupo'] = $datos['whatsapp_grupo'] ?? '';
        $datos['tipo_voluntariado'] = $datos['tipo_voluntariado'] ?? 'social';
        $datos['docente_id'] = $datos['docente_id'] ?? null;
        
        if (empty($datos['fecha_inicio']) || empty($datos['fecha_fin'])) {
            return ['success' => false, 'error' => 'Las fechas son obligatorias'];
        }
        
        if ($datos['fecha_fin'] < $datos['fecha_inicio']) {
            return ['success' => false, 'error' => 'La fecha de fin no puede ser anterior a la de inicio'];
        }
        
        if (!is_numeric($datos['cupos']) || $datos['cupos'] <= 0) {
            return ['success' => false, 'error' => 'Los cupos deben ser un número positivo'];
        }
        
        // Procesar imagen si existe
        $nombreImagen = '';
        if ($archivo && $archivo['error'] == 0) {
            $resultado = $this->procesarImagen($archivo);
            if (!$resultado['success']) {
                return $resultado;
            }
            $nombreImagen = $resultado['nombre'];
        }
        
        $datos['imagen'] = $nombreImagen;
        $datos['admin_id'] = $adminId;
        
        // Crear convocatoria
        $creado = $this->convocatoriaRepo->crear($datos);
        
        if ($creado) {
            return ['success' => true, 'mensaje' => 'Convocatoria creada exitosamente'];
        }
        
        return ['success' => false, 'error' => 'Error al crear la convocatoria'];
    }
    
    public function actualizar($id, $datos, $archivo = null) {
        // Validar que existe
        $convocatoria = $this->obtenerPorId($id);
        if (!$convocatoria) {
            return ['success' => false, 'error' => 'Convocatoria no encontrada'];
        }
        
        // Validaciones
        if (empty($datos['titulo']) || empty($datos['descripcion'])) {
            return ['success' => false, 'error' => 'Título y descripción son obligatorios'];
        }
        
        // Valores por defecto para campos opcionales
        $datos['lugar'] = $datos['lugar'] ?? '';
        $datos['contacto_responsable'] = $datos['contacto_responsable'] ?? '';
        $datos['telefono_contacto'] = $datos['telefono_contacto'] ?? '';
        $datos['whatsapp_grupo'] = $datos['whatsapp_grupo'] ?? '';
        $datos['tipo_voluntariado'] = $datos['tipo_voluntariado'] ?? 'social';
        $datos['docente_id'] = $datos['docente_id'] ?? null; 
        
        if (empty($datos['fecha_inicio']) || empty($datos['fecha_fin'])) {
            return ['success' => false, 'error' => 'Las fechas son obligatorias'];
        }
        
        if ($datos['fecha_fin'] < $datos['fecha_inicio']) {
            return ['success' => false, 'error' => 'La fecha de fin no puede ser anterior a la de inicio'];
        }
        
        if (!is_numeric($datos['cupos']) || $datos['cupos'] <= 0) {
            return ['success' => false, 'error' => 'Los cupos deben ser un número positivo'];
        }
        
        // Procesar nueva imagen si existe
        if ($archivo && $archivo['error'] == 0) {
            $resultado = $this->procesarImagen($archivo);
            if (!$resultado['success']) {
                return $resultado;
            }
            
            // Eliminar imagen anterior si existe
            if (!empty($convocatoria->imagen)) {
                $rutaAnterior = __DIR__ . '/../../public/uploads/' . $convocatoria->imagen;
                if (file_exists($rutaAnterior)) {
                    unlink($rutaAnterior);
                }
            }
            
            $datos['imagen'] = $resultado['nombre'];
        } else {
            // Mantener imagen anterior
            $datos['imagen'] = $convocatoria->imagen;
        }
        
        // Actualizar
        $actualizado = $this->convocatoriaRepo->actualizar($id, $datos);
        
        if ($actualizado) {
            return ['success' => true, 'mensaje' => 'Convocatoria actualizada exitosamente'];
        }
        
        return ['success' => false, 'error' => 'Error al actualizar la convocatoria'];
    }
    
    public function eliminar($id) {
        $convocatoria = $this->obtenerPorId($id);
        if (!$convocatoria) {
            return ['success' => false, 'error' => 'Convocatoria no encontrada'];
        }

        // Eliminar imagen si existe
        if (!empty($convocatoria->imagen)) {
            $ruta = __DIR__ . '/../../public/uploads/' . $convocatoria->imagen;
            if (file_exists($ruta)) {
                unlink($ruta);
            }
        }
        
        $eliminado = $this->convocatoriaRepo->eliminar($id);
        
        if ($eliminado) {
            return ['success' => true, 'mensaje' => 'Convocatoria eliminada exitosamente'];
        }
        
        return ['success' => false, 'error' => 'Error al eliminar la convocatoria'];
    }
    
    private function procesarImagen($archivo) {
        // Validar tipo de archivo
        $tiposPermitidos = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($archivo['type'], $tiposPermitidos)) {
            return ['success' => false, 'error' => 'Solo se permiten imágenes JPG, JPEG o PNG'];
        }
        
        // Validar tamaño (5MB máximo)
        if ($archivo['size'] > 5242880) {
            return ['success' => false, 'error' => 'La imagen no debe superar los 5MB'];
        }
        
        // Generar nombre único
        $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $nombreArchivo = uniqid() . '.' . $extension;
        $rutaDestino = __DIR__ . '/../../public/uploads/' . $nombreArchivo;
        
        // Mover archivo
        if (!move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
            return ['success' => false, 'error' => 'Error al subir la imagen'];
        }
        
        return ['success' => true, 'nombre' => $nombreArchivo];
    }
    
    public function cambiarEstado($id, $nuevoEstado) {
        $convocatoria = $this->obtenerPorId($id);
        if (!$convocatoria) {
            return ['success' => false, 'error' => 'Convocatoria no encontrada'];
        }
        
        $actualizado = $this->convocatoriaRepo->cambiarEstado($id, $nuevoEstado);
        
        if ($actualizado) {
            $mensaje = $nuevoEstado == 'activa' ? 'Convocatoria activada' : 'Convocatoria desactivada';
            return ['success' => true, 'mensaje' => $mensaje];
        }
        
        return ['success' => false, 'error' => 'Error al cambiar el estado'];
    }

    public function obtenerPorDocente($docenteId) {
        $convocatorias = $this->convocatoriaRepo->obtenerPorDocente($docenteId);
        return $this->cargarDatosDocente($convocatorias); 
    }

    public function obtenerPorIdConDocente($id) {
        return $this->obtenerPorId($id); 
    }

    /**
     * Finalizar convocatoria y otorgar insignias
     */
    public function finalizarConvocatoria($id) {
        try {
            // Verificar que existe
            $convocatoria = $this->obtenerPorId($id);
            if (!$convocatoria) {
                return ['success' => false, 'error' => 'Convocatoria no encontrada'];
            }

            // Verificar que tenga insignia asignada
            if (empty($convocatoria->insignia_id)) {
                return ['success' => false, 'error' => 'Esta convocatoria no tiene insignia asignada'];
            }

            // Obtener postulaciones aprobadas
            require_once __DIR__ . '/../Repositories/PostulacionRepository.php';
            $postulacionRepo = new PostulacionRepository();
            $postulacionesAprobadas = $postulacionRepo->obtenerAprobadasPorConvocatoria($id);

            if (empty($postulacionesAprobadas)) {
                return ['success' => false, 'error' => 'No hay estudiantes aprobados en esta convocatoria'];
            }

            // Obtener datos de la insignia
            require_once __DIR__ . '/../Services/InsigniaService.php';
            $insigniaService = new InsigniaService();
            $insignia = $insigniaService->obtenerPorId($convocatoria->insignia_id);

            if (!$insignia) {
                return ['success' => false, 'error' => 'Insignia no encontrada'];
            }

            $criterioAsistencia = $insignia['criterio_asistencia'] ?? 80;

            // Procesar cada estudiante
            require_once __DIR__ . '/../Models/Mongo/Asistencia.php';
            require_once __DIR__ . '/../Models/Mongo/UsuarioInsignia.php';
            
            $asistenciaModel = new Asistencia();
            $usuarioInsigniaModel = new UsuarioInsignia();
            
            $insigniasOtorgadas = 0;
            $estudiantesProcesados = 0;

            foreach ($postulacionesAprobadas as $postulacion) {
                $estudianteId = $postulacion->estudiante_id;
                
                // Calcular asistencias
                $totalAsistencias = $asistenciaModel->contarTotal($id, $estudianteId);
                $asistenciasPresentes = $asistenciaModel->contarPresentes($id, $estudianteId);
                
                $porcentajeAsistencia = 0;
                if ($totalAsistencias > 0) {
                    $porcentajeAsistencia = ($asistenciasPresentes / $totalAsistencias) * 100;
                }

                // Verificar si cumple el criterio
                if ($porcentajeAsistencia >= $criterioAsistencia) {
                    // Verificar si ya tiene la insignia
                    $yaLaTiene = $usuarioInsigniaModel->tieneInsignia($estudianteId, $convocatoria->insignia_id);
                    
                    if (!$yaLaTiene) {
                        // Otorgar insignia
                        $usuarioInsigniaModel->otorgarConConvocatoria(
                            $estudianteId, 
                            $convocatoria->insignia_id, 
                            $id
                        );
                        $insigniasOtorgadas++;
                    }
                }
                
                $estudiantesProcesados++;
            }

            // Cambiar estado de convocatoria a inactiva (finalizada)
            $this->convocatoriaRepo->cambiarEstado($id, 'inactiva');

            $mensaje = "Convocatoria finalizada. Se procesaron {$estudiantesProcesados} estudiantes y se otorgaron {$insigniasOtorgadas} insignias.";
            
            return [
                'success' => true, 
                'mensaje' => $mensaje,
                'estudiantes_procesados' => $estudiantesProcesados,
                'insignias_otorgadas' => $insigniasOtorgadas
            ];

        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Error al finalizar convocatoria: ' . $e->getMessage()];
        }
    }
}