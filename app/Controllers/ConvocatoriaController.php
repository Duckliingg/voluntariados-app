<?php
// app/Controllers/ConvocatoriaController.php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Services/ConvocatoriaService.php';
require_once __DIR__ . '/../Services/InsigniaService.php';
require_once __DIR__ . '/../Repositories/UsuarioRepository.php';

class ConvocatoriaController extends BaseController {

    private $convocatoriaService;
    private $insigniaService;
    private $usuarioRepository;

    public function __construct() {
        $this->convocatoriaService = new ConvocatoriaService();
        $this->insigniaService = new InsigniaService();
        $this->usuarioRepository = new UsuarioRepository();
    }

    // Admin - Listar todas las convocatorias
    public function index() {
        $this->requireAuth('admin');

        $convocatorias = $this->convocatoriaService->obtenerTodas();

        $this->render('admin/convocatorias/index', [
            'pageTitle' => 'Gestión de Convocatorias',
            'baseUrl' => '../../',
            'convocatorias' => $convocatorias,
            'success' => $this->getFlashMessage('success'),
            'error' => $this->getFlashMessage('error')
        ]);
    }

    // Admin - Mostrar formulario de crear
    public function crear() {
        $this->requireAuth('admin');

        // Obtener lista de docentes y insignias activas
        $docentes = $this->usuarioRepository->obtenerDocentes();
        $insignias = $this->insigniaService->obtenerActivas();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos = [
                'titulo' => trim($_POST['titulo']),
                'descripcion' => trim($_POST['descripcion']),
                'fecha_inicio' => $_POST['fecha_inicio'],
                'fecha_fin' => $_POST['fecha_fin'],
                'cupos' => $_POST['cupos'],
                'lugar' => trim($_POST['lugar'] ?? ''),
                'contacto_responsable' => trim($_POST['contacto_responsable'] ?? ''),
                'telefono_contacto' => trim($_POST['telefono_contacto'] ?? ''),
                'whatsapp_grupo' => trim($_POST['whatsapp_grupo'] ?? ''),
                'tipo_voluntariado' => $_POST['tipo_voluntariado'] ?? 'social',
                'docente_id' => $_POST['docente_id'] ?? null,
                'insignia_id' => $_POST['insignia_id'] ?? null // NUEVO
            ];

            $archivo = isset($_FILES['imagen']) ? $_FILES['imagen'] : null;
            $adminId = $_SESSION['admin_id'];

            $resultado = $this->convocatoriaService->crear($datos, $archivo, $adminId);

            if ($resultado['success']) {
                $this->setFlashMessage('success', $resultado['mensaje']);
                $this->redirect('index.php');
            }

            $error = $resultado['error'];
        }

        $this->render('admin/convocatorias/crear', [
            'pageTitle' => 'Crear Convocatoria',
            'baseUrl' => '../../',
            'docentes' => $docentes,
            'insignias' => $insignias, // NUEVO
            'error' => $error ?? null,
            'postData' => $_POST ?? []
        ]);
    }

    // Admin - Mostrar formulario de editar
    public function editar() {
        $this->requireAuth('admin');

        if (!isset($_GET['id'])) {
            $this->redirect('index.php');
        }

        $id = $_GET['id'];
        $convocatoria = $this->convocatoriaService->obtenerPorId($id);

        if (!$convocatoria) {
            $this->redirect('index.php');
        }

        // Obtener lista de docentes y insignias activas
        $docentes = $this->usuarioRepository->obtenerDocentes();
        $insignias = $this->insigniaService->obtenerActivas();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos = [
                'titulo' => trim($_POST['titulo']),
                'descripcion' => trim($_POST['descripcion']),
                'fecha_inicio' => $_POST['fecha_inicio'],
                'fecha_fin' => $_POST['fecha_fin'],
                'cupos' => $_POST['cupos'],
                'lugar' => trim($_POST['lugar'] ?? ''),
                'contacto_responsable' => trim($_POST['contacto_responsable'] ?? ''),
                'telefono_contacto' => trim($_POST['telefono_contacto'] ?? ''),
                'whatsapp_grupo' => trim($_POST['whatsapp_grupo'] ?? ''),
                'tipo_voluntariado' => $_POST['tipo_voluntariado'] ?? 'social',
                'docente_id' => $_POST['docente_id'] ?? null,
                'insignia_id' => $_POST['insignia_id'] ?? null // NUEVO
            ];

            $archivo = isset($_FILES['imagen']) ? $_FILES['imagen'] : null;

            $resultado = $this->convocatoriaService->actualizar($id, $datos, $archivo);

            if ($resultado['success']) {
                $convocatoria = $this->convocatoriaService->obtenerPorId($id);
                $success = $resultado['mensaje'];
            } else {
                $error = $resultado['error'];
            }
        }

        $this->render('admin/convocatorias/editar', [
            'pageTitle' => 'Editar Convocatoria',
            'baseUrl' => '../../',
            'convocatoria' => $convocatoria,
            'docentes' => $docentes,
            'insignias' => $insignias, // NUEVO
            'success' => $success ?? null,
            'error' => $error ?? null
        ]);
    }

    // Admin - Eliminar convocatoria
    public function eliminar() {
        $this->requireAuth('admin');

        if (!isset($_GET['id'])) {
            $this->redirect('index.php');
        }

        $id = $_GET['id'];
        $convocatoria = $this->convocatoriaService->obtenerPorId($id);

        if (!$convocatoria) {
            $this->setFlashMessage('error', 'Convocatoria no encontrada');
            $this->redirect('index.php');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $resultado = $this->convocatoriaService->eliminar($id);

            if ($resultado['success']) {
                $this->setFlashMessage('success', $resultado['mensaje']);
            } else {
                $this->setFlashMessage('error', $resultado['error']);
            }

            $this->redirect('index.php');
        }

        $this->render('admin/convocatorias/eliminar', [
            'pageTitle' => 'Eliminar Convocatoria',
            'baseUrl' => '../../',
            'convocatoria' => $convocatoria
        ]);
    }

    // Admin - Cambiar estado de convocatoria
    public function cambiarEstado() {
        $this->requireAuth('admin');

        if (!isset($_GET['id']) || !isset($_GET['estado'])) {
            $this->redirect('index.php');
        }

        $id = $_GET['id'];
        $nuevoEstado = $_GET['estado'];

        // Validar estado
        if (!in_array($nuevoEstado, ['activa', 'inactiva'])) {
            $this->setFlashMessage('error', 'Estado inválido');
            $this->redirect('index.php');
        }

        $resultado = $this->convocatoriaService->cambiarEstado($id, $nuevoEstado);

        if ($resultado['success']) {
            $this->setFlashMessage('success', $resultado['mensaje']);
        } else {
            $this->setFlashMessage('error', $resultado['error']);
        }

        $this->redirect('index.php');
    }

    // Estudiante - Listar convocatorias disponibles
    public function listarEstudiantes() {
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

    // Docente - Listar convocatorias asignadas
    public function listarDocente() {
        $this->requireAuth('docente', '../login.php');

        $docenteId = $_SESSION['docente_id'];
        $convocatorias = $this->convocatoriaService->obtenerPorDocente($docenteId);

        $this->render('docente/convocatorias/index', [
            'pageTitle' => 'Mis Convocatorias',
            'baseUrl' => '../../',
            'convocatorias' => $convocatorias,
            'success' => $this->getFlashMessage('success'),
            'error' => $this->getFlashMessage('error')
        ]);
    }

    // Admin - Finalizar convocatoria y otorgar insignias
    public function finalizar() {
        $this->requireAuth('admin');

        if (!isset($_GET['id'])) {
            $this->setFlashMessage('error', 'ID de convocatoria no válido');
            $this->redirect('index.php');
        }

        $id = $_GET['id'];
        $convocatoria = $this->convocatoriaService->obtenerPorId($id);

        if (!$convocatoria) {
            $this->setFlashMessage('error', 'Convocatoria no encontrada');
            $this->redirect('index.php');
        }

        // Mostrar confirmación
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->render('admin/convocatorias/finalizar', [
                'pageTitle' => 'Finalizar Convocatoria',
                'baseUrl' => '../../',
                'convocatoria' => $convocatoria
            ]);
            return;
        }

        // Procesar finalización
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $resultado = $this->convocatoriaService->finalizarConvocatoria($id);

            if ($resultado['success']) {
                $mensaje = $resultado['mensaje'];
                if (isset($resultado['insignias_otorgadas']) && $resultado['insignias_otorgadas'] > 0) {
                    $mensaje .= " ¡{$resultado['insignias_otorgadas']} insignias fueron otorgadas!";
                }
                $this->setFlashMessage('success', $mensaje);
            } else {
                $this->setFlashMessage('error', $resultado['error']);
            }

            $this->redirect('index.php');
        }
    }
}