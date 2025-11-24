<?php
class CategoryController
{
    public $modelDanhMuc;
    public function __construct()
    {
        $this->modelDanhMuc = new Category();
    }
    public function index()
    {
        $listDanhMuc = $this->modelDanhMuc->getAllDanhMuc();
        require_once './views/category/index.php';
    }

    public function editForm()
    {
        $id = $_GET['id_danh_muc'];
        $danhMuc = $this->modelDanhMuc->getDetailDanhMuc($id);
        if ($danhMuc) {
            require_once './views/category/editForm.php';
        } else {
            header("Location: " . BASE_URL . '?route=/categories');
        }
    }
}
