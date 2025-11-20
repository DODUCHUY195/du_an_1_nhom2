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
        $id = $_GET['category_id'];
        $danhMuc = $this->modelDanhMuc->getDetailDanhMuc($id);

            require_once './views/category/editForm.php';
        
    }

public function postEdit(){
        if($_SERVER['REQUEST_METHOD']== 'POST'){
            $id = $_POST['category_id'] ?? null;
            $ten_danh_muc = $_POST['category_name'] ?? null;
            $mo_ta = $_POST['description'] ?? null;
            $errors = [];
            if(empty($ten_danh_muc)){
                $errors['category_name']='Tên danh mục không đc để trống';
            }

            if(empty($errors)){
                $this->modelDanhMuc->updateDanhMuc($id,$ten_danh_muc,$mo_ta);
                header("Location: ". BASE_URL . '?route=/categories' );
                exit();
            }else{
                $danhMuc = ['category_id'=>$id, 'category_name'=>$ten_danh_muc,'description'=>$mo_ta];
                 require_once './views/category/editForm.php';
            }
        }
       
    }
}
