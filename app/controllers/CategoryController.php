<?php
require_once __DIR__ . '/../models/Category.php';

class CategoryController
{
    public function index()
    {
        $m = new Category();
        $categories = $m->all();
        require_once __DIR__ . '/../views/category/index.php';
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "?route=/categories");
            exit;
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            $_SESSION['flash'] = ['type'=>'danger','msg'=>'ID không hợp lệ'];
            header("Location: " . BASE_URL . "?route=/categories");
            exit;
        }

        $m = new Category();
        $ok = $m->delete($id);

        if ($ok) {
            $_SESSION['flash'] = ['type'=>'success','msg'=>'Xóa danh mục thành công'];
        } else {
            $_SESSION['flash'] = ['type'=>'danger','msg'=>'Xóa danh mục thất bại'];
        }

        header("Location: " . BASE_URL . "?route=/categories");
        exit;
    }
}
?>
