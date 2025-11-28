<?php
// app/Controllers/InsigniaController.php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Services/InsigniaService.php';

class InsigniaController extends BaseController {
    private $insigniaService;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->insigniaService = new InsigniaService();
    }


    // Listar todas las insignias (Admin)
    public function listar() {
        $this->requireAuth('admin');
        
        $insignias = $this->insigniaService->obtenerTodas();
        
        $this->render('admin/insignias/index', [
            'pageTitle' => 'Gestión de Insignias',
            'baseUrl' => '../../',
            'insignias' => $insignias,
            'success' => $this->getFlashMessage('success'),
            'error' => $this->getFlashMessage('error')
        ]);
    }

    // Mostrar formulario de crear insignia
    public function crear() {
        $this->requireAuth('admin');
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $resultado = $this->insigniaService->crear(
                $_POST['nombre'],
                $_POST['descripcion'],
                $_POST['categoria'],
                $_POST['icono'],
                $_POST['color'],
                (int)$_POST['criterio_asistencia']
            );

            if ($resultado['success']) {
                $this->setFlashMessage('success', 'Insignia creada exitosamente');
                $this->redirect('index.php');
            } else {
                $error = $resultado['error'];
            }
        }

        $this->render('admin/insignias/crear', [
            'pageTitle' => 'Crear Insignia',
            'baseUrl' => '../../',
            'error' => $error ?? null
        ]);
    }

    // Mostrar formulario de editar insignia
    public function editar() {
        $this->requireAuth('admin');
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->setFlashMessage('error', 'ID de insignia no válido');
            $this->redirect('index.php');
        }

        $insignia = $this->insigniaService->obtenerPorId($id);
        
        if (!$insignia) {
            $this->setFlashMessage('error', 'Insignia no encontrada');
            $this->redirect('index.php');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $resultado = $this->insigniaService->actualizar(
                $id,
                $_POST['nombre'],
                $_POST['descripcion'],
                $_POST['categoria'],
                $_POST['icono'],
                $_POST['color'],
                (int)$_POST['criterio_asistencia'],
                isset($_POST['activa'])
            );

            if ($resultado['success']) {
                $this->setFlashMessage('success', 'Insignia actualizada exitosamente');
                $this->redirect('index.php');
            } else {
                $error = $resultado['error'];
            }
        }

        $this->render('admin/insignias/editar', [
            'pageTitle' => 'Editar Insignia',
            'baseUrl' => '../../',
            'insignia' => $insignia,
            'error' => $error ?? null
        ]);
    }

    // Eliminar insignia
    public function eliminar() {
        $this->requireAuth('admin');
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? null;
            
            if (!$id) {
                $this->setFlashMessage('error', 'ID de insignia no válido');
                $this->redirect('index.php');
            }

            $resultado = $this->insigniaService->eliminar($id);

            if ($resultado['success']) {
                $this->setFlashMessage('success', 'Insignia eliminada exitosamente');
            } else {
                $this->setFlashMessage('error', $resultado['error']);
            }
        }

        $this->redirect('index.php');
    }

    // Cambiar estado activa/inactiva
    public function cambiarEstado() {
        $this->requireAuth('admin');
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? null;
            $activa = isset($_POST['activa']) && $_POST['activa'] == '1';
            
            $resultado = $this->insigniaService->cambiarEstado($id, $activa);

            if ($resultado['success']) {
                $this->setFlashMessage('success', 'Estado actualizado exitosamente');
            } else {
                $this->setFlashMessage('error', $resultado['error']);
            }
        }

        $this->redirect('index.php');
    }

    // Listar insignias activas para selector
    public function listarActivas() {
        return $this->insigniaService->obtenerActivas();
    }

        // Mostrar insignias obtenidas por el estudiante
    public function misInsignias()
    {
        // Verificar autenticación
        if (!isset($_SESSION['estudiante_id'])) {
            header('Location: ../../login.php');
            exit;
        }

        $usuarioId = (int) $_SESSION['estudiante_id'];

        // Obtener insignias desde MongoDB
        $insignias = $this->insigniaService->obtenerInsigniasUsuario($usuarioId);

        // Renderizar la vista
        $this->render('estudiantes/insignias/misInsignias', [
            'pageTitle' => 'Mis Insignias',
            'baseUrl' => '../../../',
            'insignias' => $insignias
        ]);
    }
}