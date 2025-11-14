<?php
class BaseController {
    /**
     * Render view kèm header/footer layout
     * @param string $view  Ví dụ: 'admin/home' hoặc 'tours/index'
     * @param array  $data  Mảng dữ liệu truyền vào view, sẽ extract() thành biến
     * @param string|null $layoutPath (tùy chọn) đường dẫn layout nếu muốn override
     */

    protected function render(string $view, array $data = [], string $layoutPath = null) {
        // đảm bảo session đã start (nếu dùng flash)
        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }

        // biến dữ liệu cho view
        extract($data);

        // flash message (nếu có)
        $flash = $_SESSION['flash'] ?? null;
        // nếu muốn xóa flash sau khi hiển thị:
        if (isset($_SESSION['flash'])) {
            unset($_SESSION['flash']);
        }

        // xây đường dẫn file view dựa vào APP_PATH
        // APP_PATH phải được định nghĩa (ví dụ: define('APP_PATH', dirname(__DIR__));)
        $viewFile  = rtrim(APP_PATH, '/\\') . '/views/' . ltrim($view, '/\\') . '.php';
        $header    = rtrim(APP_PATH, '/\\') . '/views/layouts/header.php';
        $footer    = rtrim(APP_PATH, '/\\') . '/views/layouts/footer.php';

        // Nếu người dùng truyền layoutPath riêng
        if ($layoutPath !== null) {
            $header = rtrim(APP_PATH, '/\\') . '/' . ltrim($layoutPath, '/\\') . '/header.php';
            $footer = rtrim(APP_PATH, '/\\') . '/' . ltrim($layoutPath, '/\\') . '/footer.php';
        }

        // kiểm tra tồn tại file view trước khi include để tránh lỗi
        if (!file_exists($viewFile)) {
            http_response_code(500);
            echo "<h1>View not found</h1><p>{$viewFile}</p>";
            return;
        }

        // include header - view - footer (nếu tồn tại)
        if (file_exists($header)) {
            require $header;
        }

        require $viewFile;

        if (file_exists($footer)) {
            require $footer;
        }
    }

    /**
     * Redirect tới một URL hoặc route (hỗ trợ route dạng '/tours' tương đối)
     * @param string $url Có thể là '/tours' hoặc 'index.php?route=/tours'
     */
    protected function redirect(string $url) {
        // nếu là route bắt đầu bằng '/' (pretty route) -> chuyển về đường dẫn tương đối so với script
        if (strpos($url, '/') === 0) {
            $scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
            // nếu scriptDir là '/', giữ nguyên
            $url = ($scriptDir === '/' ? '' : $scriptDir) . $url;
        }
        // header location - dùng absolute hoặc relative đều được
        header('Location: ' . $url);
        exit;
    }

    /**
     * Thiết lập flash message và redirect
     */
    protected function setFlashAndRedirect(string $message, string $url) {
        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }
        $_SESSION['flash'] = $message;
        $this->redirect($url);
    }
}
