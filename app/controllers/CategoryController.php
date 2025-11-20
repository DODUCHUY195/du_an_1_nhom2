<?php
require_once __DIR__ . '/../models/Category.php';

class CategoryController
{
    public $modelDanhMuc;
    public function __construct()
    {
        $this->modelDanhMuc = new Category();
    }

    // list categories
    public function index()
    {
        // hỗ trợ cả hai kiểu tên method từ các variant model
        if (method_exists($this->modelDanhMuc, 'all')) {
            $categories = $this->modelDanhMuc->all();
        } elseif (method_exists($this->modelDanhMuc, 'getAllDanhMuc')) {
            $categories = $this->modelDanhMuc->getAllDanhMuc();
        } else {
            $categories = [];
        }

        // sử dụng đường dẫn đầy đủ theo cấu trúc dự án
        require_once __DIR__ . '/../views/category/index.php';
    }

    // show add form
    public function addForm()
    {
        require_once __DIR__ . '/../views/category/addForm.php';
    }

    // xử lý thêm
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
            // lưu flash hoặc trả về form (ở đơn giản thì redirect về form)
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => implode('; ', $errors)];
            header("Location: " . BASE_URL . "?route=/categories/addForm");
            exit;
        }

        // cố gắng gọi method insert phù hợp trên model
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

    // show edit form (id via GET or param)
    public function editForm()
    {
        $id = $_GET['id'] ?? null;
        $category = null;
        if ($id) {
            if (method_exists($this->modelDanhMuc, 'find')) {
                $category = $this->modelDanhMuc->find($id);
            } elseif (method_exists($this->modelDanhMuc, 'getById')) {
                $category = $this->modelDanhMuc->getById($id);
            }
        }
        require_once __DIR__ . '/../views/category/editForm.php';
    }

    // delete (POST)
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

        // gọi method delete tương ứng
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
