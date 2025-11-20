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
        


            require_once './views/category/editForm.php';
        

    public function editForm()
    {
        


            require_once './views/category/editForm.php';
        
    }


}
