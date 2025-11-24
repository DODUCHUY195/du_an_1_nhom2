<?php
class CategoryController extends BaseController
{
    public function index()
    {
        $m = new Category();
        $cats = $m->all();
        $this->render('category/index', ['categories' => $cats]);
    }
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $this->db->prepare('INSERT INTO category (category_name,description,parent_id) VALUES(:name,:desc,:parent)');
            // But BaseController doesn't have db; use model
            $model = new Category();
            $db = $model->db;
            $stmt = $db->prepare('INSERT INTO category (category_name,description,parent_id) VALUES(:name,:desc,:parent)');
            $stmt->execute([':name' => $_POST['category_name'], ':desc' => $_POST['description'], ':parent' => $_POST['parent_id'] ?: null]);
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Danh mục tạo thành công'];
            $this->redirect('/categories');
        }
        $this->render('category/form');
    }
}
