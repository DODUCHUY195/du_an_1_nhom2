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

    public function addForm()
    {

            require_once './views/category/addForm.php';
        
    }

    public function postAdd(){
        if($_SERVER['REQUEST_METHOD']== 'POST'){
            $ten_danh_muc = $_POST['category_name'];
            $mo_ta = $_POST['description'];
            $errors = [];
            if(empty($ten_danh_muc)){
                $errors['category_name']='Tên danh mục không đc để trống';
            }

            if(empty($errors)){
                $this->modelDanhMuc->insertDanhMuc($ten_danh_muc,$mo_ta);
                header("Location: ". BASE_URL . '?act=danhmuc' );
                exit();
            }else{
                 require_once './views/danhmuc/addDanhMuc.php';
            }
        }
    }
    public function editForm()
    {
        

            require_once './views/category/editForm.php';
        
    }


}
