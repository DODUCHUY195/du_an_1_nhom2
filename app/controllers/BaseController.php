<?php
class BaseController 
{
    protected function render($view, $data = [])
    {
        if (!empty($data)) extract($data, EXTR_OVERWRITE);
        $viewFile = rtrim(PATH_VIEW, '/\\') . '/' . ltrim($view, '/\\') . '.php';
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            // debug nhanh nếu view không tồn tại
            die("View not found: $viewFile");
        }
    }

    protected function redirect($path)
    {
        // Nếu path là URL tuyệt đối
        if (preg_match('#^https?://#', $path)) {
            header('Location: ' . $path);
            exit;
        }
        // Dùng BASE_URL từ env.php, bạn có thể điều chỉnh BASE_URL nếu cần
        $base = rtrim(BASE_URL, '/');
        $target = $base . '/' . ltrim($path, '/');
        header('Location: ' . $target);
        exit;
    }

    protected function setFlash($type, $msg)
    {
        $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
    }
}
?>