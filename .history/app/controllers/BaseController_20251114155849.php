<?php
class BaseController {
    protected function render(string $view, array $data = [], string $layoutPath = null) {
        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }

        // extract nhưng không ghi đè biến đã có
        extract($data, EXTR_SKIP);

        $flash = $_SESSION['flash'] ?? null;
        if (isset($_SESSION['flash'])) {
            unset($_SESSION['flash']);
        }

        $viewFile  = rtrim(APP_PATH, '/\\') . '/' . ltrim($view, '/\\') . '.php';
        $header    = rtrim(APP_PATH, '/\\') . '/views/layouts/header.php';
        $footer    = rtrim(APP_PATH, '/\\') . '/views/layouts/footer.php';
        $sidebar    = rtrim(APP_PATH, '/\\') . '/views/layouts/sidebar.php';
        $navbar    = rtrim(APP_PATH, '/\\') . '/views/layouts/navbar.php';
        if ($layoutPath !== null) {
            $header = rtrim(APP_PATH, '/\\') . '/' . ltrim($layoutPath, '/\\') . '/header.php';
            
            $sidebar = rtrim(APP_PATH, '/\\') . '/' . ltrim($layoutPath, '/\\') . '/sidebar.php';
            $navbar = rtrim(APP_PATH, '/\\') . '/' . ltrim($layoutPath, '/\\') . '/navbar.php';
            $footer = rtrim(APP_PATH, '/\\') . '/' . ltrim($layoutPath, '/\\') . '/footer.php';
        }

        if (!file_exists($viewFile)) {
            http_response_code(500);
            // dev: show path, prod: log instead
            echo "<h1>View not found</h1><p>{$viewFile}</p>";
            return;
        }

        if (file_exists($header)) {
            require $header;
        }

        require $viewFile;

        if (file_exists($footer)) {
            require $footer;
        }

        if (file_exists($sidebar)) {
            require $sidebar;
        }

        if (file_exists($navbar)) {
            require $navbar;
        }
    }

    protected function redirect(string $url) {
        // Nếu route nội bộ bắt đầu bằng '/', nối script dir
        if (strpos($url, '/') === 0 && stripos($url, 'http') !== 0) {
            $scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
            $url = ($scriptDir === '/' ? '' : $scriptDir) . $url;
        }

        if (headers_sent($file, $line)) {
            // headers đã gửi — không thể redirect bằng header(); có thể dùng JS fallback
            error_log("Redirect failed, headers already sent in $file on line $line. Target: $url");
            echo "<script>location.href='" . htmlspecialchars($url, ENT_QUOTES) . "';</script>";
            exit;
        }

        header('Location: ' . $url);
        exit;
    }

    protected function setFlashAndRedirect(string $message, string $url) {
        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }
        $_SESSION['flash'] = $message;
        $this->redirect($url);
    }
}
