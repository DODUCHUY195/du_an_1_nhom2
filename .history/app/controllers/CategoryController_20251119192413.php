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
        require_once './views/category/index.php';
    }
   
}
