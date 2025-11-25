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

    public function editForm()
    {
        $id = $_GET['tour_id'];
        $listTour = $this->modelTour->getDetailTour($id);
        require_once './views/tours/editForm.php';
    }

    public function postEdit(){
        if($_SERVER['REQUEST_METHOD']== 'POST'){
            $id = $_POST['tour_id'] ?? null;
           $ten = $_POST['tour_name']?? null;
            $gia = $_POST['price']?? null;
            $duration = $_POST['duration_days']?? null;
            $mo_ta = $_POST['description']?? null;
            $trang_thai = $_POST['status']?? null;
            $thoi_gian_tao = $_POST['created_at']?? null;
            $errors = []?? null;
            if(empty($ten_danh_muc)){
                $errors['category_name']='Tên danh mục không đc để trống';
            }

            
                $this->modelTour->updateTour($id,$ten,$gia,$duration, $mo_ta,$trang_thai,$thoi_gian_tao);
                header("Location: ". BASE_URL . '?route=/tours' );
                exit();
            
                $Tour = ['tour_id'=>$id, 'tour_name'=>$ten,'price'=>$gia,'duration_days'=>$duration,'description'=>$mo_ta,'status'=>$trang_thai,'created_at'=>$thoi_gian_tao];
                 require_once './views/tours/editForm.php';
           
        }
       
    }

    
}
