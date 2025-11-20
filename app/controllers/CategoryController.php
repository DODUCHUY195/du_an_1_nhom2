<?php
require_once __DIR__ . '/../models/Category.php';

class CategoryController
{
    public $modelDanhMuc;
    public function __construct()
    {
        $this->modelDanhMuc = new Category();
    }

    // Hiển thị danh sách - hỗ trợ cả hai kiểu model method
    public function index()
    {
        if (method_exists($this->modelDanhMuc, 'all')) {
            $categories = $this->modelDanhMuc->all();
        } elseif (method_exists($this->modelDanhMuc, 'getAllDanhMuc')) {
            $listDanhMuc = $this->modelDanhMuc->getAllDanhMuc();
        } else {
            $categories = $listDanhMuc = [];
        }

        require_once __DIR__ . '/../views/category/index.php';
    }

    // Hiển thị form thêm
    public function addForm()
    {
        require_once __DIR__ . '/../views/category/addForm.php';
    }

    // Xử lý thêm
    public function postAdd()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "?route=/categories/addForm");
            exit;
        }

        $ten_danh_muc = trim($_POST['category_name'] ?? '');
        $mo_ta = trim($_POST['description'] ?? '');
        $errors = [];

        if ($ten_danh_muc === '') {
            $errors[] = 'Tên danh mục không được để trống';
        }

        if (!empty($errors)) {
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => implode('; ', $errors)];
            header("Location: " . BASE_URL . "?route=/categories/addForm");
            exit;
        }

        if (method_exists($this->modelDanhMuc, 'insertDanhMuc')) {
            $ok = $this->modelDanhMuc->insertDanhMuc($ten_danh_muc, $mo_ta);
        } elseif (method_exists($this->modelDanhMuc, 'insert')) {
            $ok = $this->modelDanhMuc->insert(['category_name' => $ten_danh_muc, 'description' => $mo_ta]);
        } else {
            $ok = false;
        }

        if ($ok) {
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Thêm danh mục thành công'];
            header("Location: " . BASE_URL . "?route=/categories");
            exit;
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Thêm thất bại'];
            header("Location: " . BASE_URL . "?route=/categories/addForm");
            exit;
        }
    }

    // Hiển thị form sửa
    public function editForm()
    {
        $id = $_GET['category_id'] ?? ($_GET['id'] ?? null);
        $danhMuc = null;
        if ($id) {
            if (method_exists($this->modelDanhMuc, 'getDetailDanhMuc')) {
                $danhMuc = $this->modelDanhMuc->getDetailDanhMuc($id);
            } elseif (method_exists($this->modelDanhMuc, 'find')) {
                $danhMuc = $this->modelDanhMuc->find($id);
            }
        }
        require_once __DIR__ . '/../views/category/editForm.php';
    }

    // Xử lý sửa
    public function postEdit()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "?route=/categories");
            exit;
        }

        $id = $_POST['category_id'] ?? null;
        $ten_danh_muc = trim($_POST['category_name'] ?? '');
        $mo_ta = trim($_POST['description'] ?? '');
        $errors = [];

        if ($ten_danh_muc === '') {
            $errors[] = 'Tên danh mục không được để trống';
        }

        if (!empty($errors)) {
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => implode('; ', $errors)];
            header("Location: " . BASE_URL . "?route=/categories/editForm&category_id=" . urlencode($id));
            exit;
        }

        if (method_exists($this->modelDanhMuc, 'updateDanhMuc')) {
            $ok = $this->modelDanhMuc->updateDanhMuc($id, $ten_danh_muc, $mo_ta);
        } elseif (method_exists($this->modelDanhMuc, 'update')) {
            $ok = $this->modelDanhMuc->update($id, ['category_name' => $ten_danh_muc, 'description' => $mo_ta]);
        } else {
            $ok = false;
        }

        if ($ok) {
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Cập nhật danh mục thành công'];
            header("Location: " . BASE_URL . "?route=/categories");
            exit;
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Cập nhật thất bại'];
            header("Location: " . BASE_URL . "?route=/categories/editForm&category_id=" . urlencode($id));
            exit;
        }
    }

    // Xóa (POST)
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

        if (method_exists($this->modelDanhMuc, 'delete')) {
            $ok = $this->modelDanhMuc->delete($id);
        } elseif (method_exists($this->modelDanhMuc, 'deleteDanhMuc')) {
            $ok = $this->modelDanhMuc->deleteDanhMuc($id);
        } else {
            $ok = false;
        }

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
