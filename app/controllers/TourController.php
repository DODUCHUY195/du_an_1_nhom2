<?php
class TourController extends BaseController
{
    public function index()
    {
        $m = new Tour();
        $tours = $m->all();
        $this->render('tours/index', ['tours' => $tours]);
    }
    public function create()
    {
        $catM = new Category();
        $cats = $catM->all();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                ':category_id' => $_POST['category_id'] ?? null,
                ':tour_code' => $_POST['tour_code'],
                ':tour_name' => $_POST['tour_name'],
                ':price' => $_POST['price'],
                ':duration_days' => $_POST['duration_days'],
                ':description' => $_POST['description'],
                ':status' => $_POST['status'] ?? 'draft'
            ];
            $m = new Tour();
            $m->insert($data);
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Tour được tạo'];
            $this->redirect('/tours');
        }
        $this->render('tours/form', ['categories' => $cats]);
    }

    public function edit($id)
    {
        // Lấy danh mục
        $catM = new Category();
        $cats = $catM->all();
        
        // Lấy tour cần edit
        $m = new Tour();
        $tour = $m->find($id);
        
        // Kiểm tra tour có tồn tại không
        if (!$tour) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Tour không tồn tại'];
            $this->redirect('/tours');
            return;
        }
        
        // Xử lý POST (update)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate
            if (empty($_POST['tour_code']) || empty($_POST['tour_name'])) {
                $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Mã tour và tên tour không được để trống'];
                $this->redirect('/tours/edit/' . $id);
                return;
            }
            
            $data = [
                ':category_id' => $_POST['category_id'] ?? null,
                ':tour_code' => $_POST['tour_code'],
                ':tour_name' => $_POST['tour_name'],
                ':price' => $_POST['price'],
                ':duration_days' => $_POST['duration_days'],
                ':description' => $_POST['description'],
                ':status' => $_POST['status'] ?? 'draft'
            ];
            
            $m->update($id, $data);
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Tour được cập nhật'];
            $this->redirect('/tours');
            return;
        }
        
        // Hiển thị form edit
        $this->render('tours/form', ['tour' => $tour, 'categories' => $cats]);
    }
}
