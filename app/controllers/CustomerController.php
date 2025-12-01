<?php
class CustomerController {
    private $userModel;
    private $bookingModel;

    public function __construct() {
        $this->userModel = new Account();
        $this->bookingModel = new Booking();
    }

    
    // Danh sách + tìm kiếm
    public function index()
    {
        $keyword = $_GET['keyword'] ?? '';
        $customers = $this->userModel->search($keyword);
    
        require_once './views/customer/index.php';
     
    }

     // Chi tiết
    public function detail($user_id)
    {
        if (!$user_id) {
            echo "<p style='color:red'>Thiếu user_id</p>";
            return;
        }
        $user = $this->userModel->find($user_id);
        if (!$user) {
            echo "<p style='color:red'>Không tìm thấy khách hàng</p>";
            return;
        }
        
       
        require_once './views/customer/detail.php';
      
    }

    // Form thêm
    public function createForm()
    {
       
        require_once './views/customer/createForm.php';
  
    }


    public function postCreate()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "?route=/managerCustomer");
            exit;
        }

        $data = [
            'full_name' => trim($_POST['full_name'] ?? ''),
            'email'     => trim($_POST['email'] ?? ''),
            'phone'     => trim($_POST['phone'] ?? ''),
            'password'  => $_POST['password'] ?? '',
            'activated' => isset($_POST['activated']) ? 1 : 0,
        ];

        if ($data['full_name'] === '' || $data['email'] === '') {
            echo "<p style='color:red'>Vui lòng nhập đầy đủ tên và email</p>";
            return;
        }

        $ok = $this->userModel->create($data);
        header("Location: " . BASE_URL . "?route=/managerCustomer");
        exit;
    }

    // Xử lý sửa
    public function postEdit()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "?route=/managerCustomer");
            exit;
        }

        $user_id = $_POST['user_id'] ?? null;
        if (!$user_id) {
            echo "<p style='color:red'>Thiếu user_id</p>";
            return;
        }

        $data = [
            'full_name' => trim($_POST['full_name'] ?? ''),
            'email'     => trim($_POST['email'] ?? ''),
            'phone'     => trim($_POST['phone'] ?? ''),
            // Nếu có nhập mật khẩu mới thì cập nhật
            'password'  => $_POST['password'] ?? null,
            'activated' => isset($_POST['activated']) ? 1 : 0,
        ];

        $ok = $this->userModel->update($user_id, $data);
        header("Location: " . BASE_URL . "?route=/managerCustomer/detail&user_id=" . $user_id);
        exit;
    }

     // Form sửa
    public function editForm($user_id)
    {
        if (!$user_id) {
            echo "<p style='color:red'>Thiếu user_id</p>";
            return;
        }
        $user = $this->userModel->find($user_id);
        if (!$user) {
            echo "<p style='color:red'>Không tìm thấy khách hàng</p>";
            return;
        }
       
        require_once './views/customer/editForm.php';
       
    }

     // Kích hoạt/Khóa
    public function toggle($user_id)
    {
        if (!$user_id) {
            echo "<p style='color:red'>Thiếu user_id</p>";
            return;
        }
        $this->userModel->toggleActivated($user_id);
        header("Location: " . BASE_URL . "?route=/managerCustomer");
        exit;
    }

    // Xóa
    public function delete($user_id)
    {
        if (!$user_id) {
            echo "<p style='color:red'>Thiếu user_id</p>";
            return;
        }
        $this->userModel->delete($user_id);
        header("Location: " . BASE_URL . "?route=/managerCustomer");
        exit;
    }
}
