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

    public function formEdit(){

    }
   
}
