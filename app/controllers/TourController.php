<?php
class TourController
{
    public $modelTour;
    public function __construct()
    {
        $this->modelTour = new Tour();
    }
    public function index()
    {
        $listTour = $this->modelTour->getAllTour();
        require_once './views/tours/index.php';
    }
    public function create()
    {
        require_once './views/tours/addForm.php';
    }

    public function edit() {

    }

    public function delete($post = [])
    {
        $id = null;
        if (!empty($post['tour_id'])) {
            $id = (int) $post['tour_id'];
        } elseif (!empty($_GET['tour_id'])) {
            $id = (int) $_GET['tour_id'];
        } elseif (!empty($_GET['id'])) {
            $id = (int) $_GET['id'];
        }

        if (!$id) {
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'ID tour không hợp lệ'];
            header('Location: ' . BASE_URL . '?route=/tours');
            exit;
        }

        try {
            $ok = $this->modelTour->delete($id);
        } catch (Exception $e) {
            $ok = false;
        }

        if ($ok) {
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Xóa tour thành công'];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Xóa tour thất bại'];
        }

        header('Location: ' . BASE_URL . '?route=/tours');
        exit;
    }
}
