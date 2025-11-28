<?php

require_once __DIR__ . '/../Repositories/ConvocatoriaRepository.php';
require_once __DIR__ . '/../Repositories/PostulacionRepository.php';
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/MySQL/Convocatoria.php';
require_once __DIR__ . '/../Models/Mongo/Asistencia.php';
require_once __DIR__ . '/../Models/Mongo/Insignia.php';
require_once __DIR__ . '/../Services/InsigniaService.php';

class DocenteController extends BaseController {
    
    private $convocatoriaModel;
    private $postulacionRepository;
    private $asistenciaModel;
    private $insigniaModel;
    private $insigniaService;
    
    public function __construct() {
        $this->convocatoriaModel = new ConvocatoriaRepository();
        $this->postulacionRepository = new PostulacionRepository();
        $this->asistenciaModel = new Asistencia();
        $this->insigniaModel = new Insignia();
        $this->insigniaService = new InsigniaService();
    }
    
    /**
     * Dashboard del docente
     */
    public function dashboard() {
        // Verificar que es docente
        if (!isset($_SESSION['docente_id'])) {
            $this->redirect('../auth/login.php');
        }
        
        // Obtener convocatorias asignadas al docente
        $convocatorias = [];
        
        $this->render('docente/dashboard', [
            'pageTitle' => 'Panel Docente',
            'convocatorias' => $convocatorias
        ]);
    }
    
    /**
     * Listar convocatorias para tomar asistencia
     */
    public function listarConvocatorias() {
        if (!isset($_SESSION['docente_id'])) {
            $this->redirect('../auth/login.php');
        }
        
        $docenteId = $_SESSION['docente_id'];
        $convocatorias = $this->convocatoriaModel->obtenerPorDocente($docenteId);

        $this->render('docente/convocatorias/index', [
            'pageTitle' => 'Mis Convocatorias',
            'convocatorias' => $convocatorias
        ]);
    }
    
    /**
     * Tomar asistencia para una convocatoria específica
     */
    public function tomarAsistencia($convocatoriaId) {
        if (!isset($_SESSION['docente_id'])) {
            $this->redirect('../auth/login.php');
        }
        
        // AQUÍ ESTÁ EL CAMBIO IMPORTANTE
        // Obtener estudiantes aprobados usando el PostulacionRepository
        $estudiantes = $this->postulacionRepository->obtenerEstudiantesAprobados($convocatoriaId);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->procesarAsistencia($convocatoriaId, $_POST);
        }
        
        $this->render('docente/asistencia/tomar', [
            'pageTitle' => 'Tomar Asistencia',
            'convocatoriaId' => $convocatoriaId,
            'estudiantes' => $estudiantes
        ]);
    }
    
    /**
     * Procesar el formulario de asistencia
     */
    private function procesarAsistencia($convocatoriaId, $postData) {
        $docenteId = $_SESSION['docente_id'];
        
        foreach ($postData['asistencia'] as $estudianteId => $asistio) {
            if ($asistio == '1') {
                // Registrar asistencia en MongoDB
                $this->asistenciaModel->registrar([
                    'convocatoria_id' => (int)$convocatoriaId,
                    'usuario_id' => (int)$estudianteId,
                    'docente_id' => (int)$docenteId,
                    'fecha_asistencia' => new MongoDB\BSON\UTCDateTime(),
                    'horas_aportadas' => 4, 
                    'estado' => 'confirmada'
                ]);
                
                // Usar el InsigniaService para otorgar insignias
                $this->otorgarInsignias($estudianteId, $convocatoriaId);
            }
        }
        
        $this->setFlashMessage('success', 'Asistencia registrada correctamente');
        $this->redirect('index.php?action=convocatorias');
    }
    

    private function otorgarInsignias($estudianteId, $convocatoriaId) {
        try {
            // Obtener tipo de convocatoria
            $convocatoria = $this->convocatoriaModel->buscarPorId($convocatoriaId);
            $tipoConvocatoria = $convocatoria->tipo_voluntariado ?? 'social';
            
            // PASA el convocatoria_id como tercer parámetro
            $insigniasOtorgadas = $this->insigniaService->otorgarInsigniasPorAsistencia(
                $estudianteId, 
                $tipoConvocatoria,
                $convocatoriaId  
            );
            
            // También evaluar y guardar insignias basadas en el historial completo
            $this->insigniaService->evaluarYGuardar($estudianteId);
            
            // Log de insignias otorgadas
            foreach ($insigniasOtorgadas as $insignia) {
                error_log("Insignia otorgada: {$insignia['nombre']} para estudiante $estudianteId en convocatoria $convocatoriaId");
            }
            
        } catch (Exception $e) {
            error_log("Error al otorgar insignias: " . $e->getMessage());
        }
    }
}