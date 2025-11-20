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
            

            
                $this->modelDanhMuc->insertDanhMuc($ten_danh_muc,$mo_ta);
                header("Location: ". BASE_URL . '?route=/categories' );
                exit();
            
                 require_once './views/category/addForm.php';
            
        }
    }
    public function editForm()
    {
        

            require_once './views/category/editForm.php';
        
    }


}
