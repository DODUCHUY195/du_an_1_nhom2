<?php
class TourController
{
    public $modelTour;
    public function __construct()
    {
        $this->modelTour = new Tour();
    }

    // danh sách tour
    public function index()
    {
        $listTour = $this->modelTour->getAllTour();
        require_once APP_PATH . '/views/tours/index.php';
    }

    // form thêm tour
    public function addForm()
    {
        require_once APP_PATH . '/views/tours/addForm.php';
    }

    // xử lý thêm tour
    public function postAdd()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ten = $_POST['tour_name'];
            $gia = $_POST['price'];
            $mota = $_POST['description'];
            $soNgay = $_POST['duration_days'];
            $status = $_POST['status'];

            // tour_code tạm thời NULL
            $code = !empty($_POST['tour_code']) ? $_POST['tour_code'] : NULL;

            $this->modelTour->insertTour($ten, $code, $gia, $mota, $soNgay, $status);

            header("Location: " . BASE_URL . "?route=/tours");
            exit();
        }
    }

    // form sửa tour
    public function editForm()
    {
        $id = $_GET['tour_id'];
        $tour = $this->modelTour->getDetailTour($id);
        require_once APP_PATH . '/views/tours/editForm.php';
    }

    // xử lý sửa tour
    public function postEdit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['tour_id'];
            $ten = $_POST['tour_name'];
            $gia = $_POST['price'];
            $duration = $_POST['duration_days'];
            $mo_ta = $_POST['description'];
            $trang_thai = $_POST['status'];
            $thoi_gian_tao = $_POST['created_at'];

            $this->modelTour->updateTour($id, $ten, $gia, $duration, $mo_ta, $trang_thai, $thoi_gian_tao);

            header("Location: " . BASE_URL . '?route=/tours');
            exit();
        }
    }
}
