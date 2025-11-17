<?php
class GuideController extends BaseController
{
    public function index()
    {
        $m = new Guide();
        $guides = $m->all();
        $this->render('guides/index', ['guides' => $guides]);
    }
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [':user_id' => $_POST['user_id'] ?: null, ':license_no' => $_POST['license_no'], ':note' => $_POST['note']];
            $m = new Guide();
            $m->create($data);
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'HDV được tạo'];
            $this->redirect('/guides');
        }
        // list users for assignment
        $u = new User();
        $users = $u->db->query('SELECT user_id, full_name FROM users')->fetchAll();
        $this->render('guides/form', ['users' => $users]);
    }
    public function edit($id)
    {
        $m = new Guide();
        $guide = $m->find($id);
        $u = new User();
        $users = $u->db->query('SELECT user_id, full_name FROM users')->fetchAll();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [':user_id' => $_POST['user_id'] ?: null, ':license_no' => $_POST['license_no'], ':note' => $_POST['note']];
            $m->update($id, $data);
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'HDV cập nhật'];
            $this->redirect('/guides');
        }
        $this->render('guides/form', ['guide' => $guide, 'users' => $users]);
    }
}
