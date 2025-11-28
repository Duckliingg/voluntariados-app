<?php
// app/Controllers/BaseController.php
class BaseController {
    protected function render($view, $data = []) {
        // Extraer variables para la vista
        extract($data);
        // Verificar si la vista existe
        $viewPath = __DIR__ . '/../../views/' . $view . '.php';
        if (!file_exists($viewPath)) {
            die("Vista no encontrada: $view");
        }
        // Renderizar vista
        include $viewPath;
    }
    protected function redirect($url) {
        header("Location: $url");
        exit;
    }
    protected function isAuthenticated($tipo) {
        return isset($_SESSION[$tipo . '_id']);
    }
    protected function requireAuth($tipo, $redirectUrl = null) {
        if (!$this->isAuthenticated($tipo)) {
            // Si es admin, redirigir a su login específico
            if ($tipo === 'admin') {
                $redirectUrl = $redirectUrl ?? "../admin/login.php";
            } else {
                // Estudiantes y docentes van al login unificado
                $redirectUrl = $redirectUrl ?? "../auth/login.php";
            }
            $this->redirect($redirectUrl);
        }
    }
    protected function setFlashMessage($type, $message) {
        $_SESSION[$type] = $message;
    }
    protected function getFlashMessage($type) {
        if (isset($_SESSION[$type])) {
            $message = $_SESSION[$type];
            unset($_SESSION[$type]);
            return $message;
        }
        return null;
    }
}
