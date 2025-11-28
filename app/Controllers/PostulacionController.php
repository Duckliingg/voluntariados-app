<?php
// app/Controllers/PostulacionController.php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Services/PostulacionService.php';
require_once __DIR__ . '/../Services/ConvocatoriaService.php';

class PostulacionController extends BaseController {
    
    private $postulacionService;
    private $convocatoriaService;
    
    public function __construct() {
        $this->postulacionService = new PostulacionService();
        $this->convocatoriaService = new ConvocatoriaService();
    }
    
    // Admin - Listar todas las postulaciones
    public function index() {
        $this->requireAuth('admin');
        
        $postulaciones = $this->postulacionService->obtenerTodas();
        
        $this->render('admin/postulaciones/index', [
            'pageTitle' => 'Gestión de Postulaciones',
            'baseUrl' => '../../',
            'postulaciones' => $postulaciones,
            'success' => $this->getFlashMessage('success'),
            'error' => $this->getFlashMessage('error')
        ]);
    }
    
    // Admin - Aprobar postulación
    public function aprobar() {
        $this->requireAuth('admin');
        
        if (!isset($_GET['id'])) {
            $this->redirect('index.php');
        }
        
        $resultado = $this->postulacionService->aprobar($_GET['id']);
        
        if ($resultado['success']) {
            $this->setFlashMessage('success', $resultado['mensaje']);
        } else {
            $this->setFlashMessage('error', $resultado['error']);
        }
        
        $this->redirect('index.php');
    }
    
    // Admin - Rechazar postulación
    public function rechazar() {
        $this->requireAuth('admin');
        
        if (!isset($_GET['id'])) {
            $this->redirect('index.php');
        }
        
        $resultado = $this->postulacionService->rechazar($_GET['id']);
        
        if ($resultado['success']) {
            $this->setFlashMessage('success', $resultado['mensaje']);
        } else {
            $this->setFlashMessage('error', $resultado['error']);
        }
        
        $this->redirect('index.php');
    }
    
    // Estudiante - Postular a convocatoria
    public function postular() {
        $this->requireAuth('estudiante', '../login.php');
        
        if (!isset($_GET['id'])) {
            $this->redirect('index.php');
        }
        
        $convocatoriaId = $_GET['id'];
        $convocatoria = $this->convocatoriaService->obtenerPorId($convocatoriaId);
        
        if (!$convocatoria) {
            $this->redirect('index.php');
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $resultado = $this->postulacionService->postular($_SESSION['estudiante_id'], $convocatoriaId);
            
            if ($resultado['success']) {
                $this->setFlashMessage('success', $resultado['mensaje']);
                $this->redirect('../historial/index.php');
            }
            
            $error = $resultado['error'];
        }
        
        $this->render('estudiantes/convocatorias/postular', [
            'pageTitle' => 'Postular a Convocatoria',
            'baseUrl' => '../../',
            'convocatoria' => $convocatoria,
            'error' => $error ?? null
        ]);
    }
    
    // Estudiante - Ver historial de postulaciones
    public function historial() {
        $this->requireAuth('estudiante', '../login.php');
        
        $postulaciones = $this->postulacionService->obtenerPorEstudiante($_SESSION['estudiante_id']);
        
        $this->render('estudiantes/historial/index', [
            'pageTitle' => 'Mi Historial de Postulaciones',
            'baseUrl' => '../../',
            'postulaciones' => $postulaciones,
            'success' => $this->getFlashMessage('success'),
            'error' => $this->getFlashMessage('error')
        ]);
    }
    
    // Estudiante - Listar convocatorias disponibles
    public function listarConvocatorias() {
        $this->requireAuth('estudiante', '../login.php');
        
        $convocatorias = $this->convocatoriaService->obtenerActivas();
        
        $this->render('estudiantes/convocatorias/index', [
            'pageTitle' => 'Convocatorias Disponibles',
            'baseUrl' => '../../',
            'convocatorias' => $convocatorias,
            'success' => $this->getFlashMessage('success'),
            'error' => $this->getFlashMessage('error')
        ]);
    }
}