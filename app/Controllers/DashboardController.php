<?php
// app/Controllers/DashboardController.php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Services/ConvocatoriaService.php';
require_once __DIR__ . '/../Services/PostulacionService.php';

class DashboardController extends BaseController {
    
    private $convocatoriaService;
    private $postulacionService;
    
    public function __construct() {
        $this->convocatoriaService = new ConvocatoriaService();
        $this->postulacionService = new PostulacionService();
    }
    
    // Dashboard Admin
    public function dashboardAdmin() {
        $this->requireAuth('admin');
        
        // Obtener estadísticas
        $convocatorias = $this->convocatoriaService->obtenerTodas();
        $postulaciones = $this->postulacionService->obtenerTodas();
        
        $totalConvocatorias = count($convocatorias);
        $totalPostulaciones = count($postulaciones);
        
        $postulacionesPendientes = 0;
        $postulacionesAprobadas = 0;
        foreach ($postulaciones as $post) {
            if ($post->estado == 'pendiente') $postulacionesPendientes++;
            if ($post->estado == 'aprobada') $postulacionesAprobadas++;
        }
        
        $this->render('admin/dashboard', [
            'pageTitle' => 'Panel Administrador',
            'baseUrl' => '../',
            'totalConvocatorias' => $totalConvocatorias,
            'totalPostulaciones' => $totalPostulaciones,
            'postulacionesPendientes' => $postulacionesPendientes,
            'postulacionesAprobadas' => $postulacionesAprobadas,
            'convocatoriasRecientes' => array_slice($convocatorias, 0, 5),
            'postulacionesRecientes' => array_slice($postulaciones, 0, 5)
        ]);
    }
    
// Dashboard Estudiante
    public function dashboardEstudiante() {
        $this->requireAuth('estudiante', '../auth/login.php');

        $estudianteId = $_SESSION['estudiante_id'];

        // Obtener estadísticas del estudiante
        $misPostulaciones = $this->postulacionService->obtenerPorEstudiante($estudianteId);
        $convocatorias = $this->convocatoriaService->obtenerActivas();

        $totalPostulaciones = count($misPostulaciones);
        $postulacionesAprobadas = 0;
        $postulacionesPendientes = 0;

        foreach ($misPostulaciones as $post) {
            if ($post->estado == 'aprobada') $postulacionesAprobadas++;
            if ($post->estado == 'pendiente') $postulacionesPendientes++;
        }

        // NUEVO: Obtener insignias del estudiante
        require_once __DIR__ . '/../Services/InsigniaService.php';
        $insigniaService = new InsigniaService();
        $insigniasEstudiante = $insigniaService->obtenerInsigniasUsuario($estudianteId);
        $totalInsignias = count($insigniasEstudiante);
        $insigniasRecientes = array_slice($insigniasEstudiante, 0, 3);

        $this->render('estudiantes/dashboard', [
            'pageTitle' => 'Panel Estudiante',
            'baseUrl' => '../',
            'totalPostulaciones' => $totalPostulaciones,
            'postulacionesAprobadas' => $postulacionesAprobadas,
            'postulacionesPendientes' => $postulacionesPendientes,
            'convocatorias' => array_slice($convocatorias, 0, 3),
            'misPostulaciones' => array_slice($misPostulaciones, 0, 5),
            'totalInsignias' => $totalInsignias,
            'insigniasRecientes' => $insigniasRecientes
        ]);
    }

    
    // Página de inicio pública
    public function index() {
        $convocatorias = $this->convocatoriaService->obtenerActivas();
        
        $this->render('public/index', [
            'pageTitle' => 'Sistema de Voluntariados - UPT',
            'baseUrl' => '/voluntariados/public/',
            'convocatorias' => array_slice($convocatorias, 0, 6)
        ]);
    }
}

