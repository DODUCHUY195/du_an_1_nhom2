<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class BookingCustomerController {
    private $model;

    public function __construct() {
        $this->model = new BookingCustomer();
    }

    

    // 📋 Hiển thị danh sách khách theo booking
    public function index($booking_id) {
        $customers = $this->model->getCustomersByBooking($booking_id);
        require_once './views/customers/index.php';
    }

    // ➕ Hiển thị form thêm khách
    public function createForm($booking_id) {
        require_once './views/customers/createForm.php';
    }

    // ✅ Xử lý thêm khách
    public function postCreate($booking_id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'booking_id' => $booking_id,
                'full_name' => $_POST['full_name'],
                'gender' => $_POST['gender'],
                'birth_year' => $_POST['birth_year'],
                'id_number' => $_POST['id_number'],
                'special_request' => $_POST['special_request'] ?? ''
            ];
            $this->model->addCustomer($data);
            header("Location: " . BASE_URL . "?route=/customerBooking&booking_id=" . $booking_id);
            exit;
        }
    }
    public function selectBooking() {

    $bookings =  $this->model->getAllBookings(); // lấy danh sách booking
    require_once './views/customers/selectBooking.php'; // view chọn booking
}

// Hiển thị form sửa khách
public function editCustomer($customer_id) {
    $customer = $this->model->getCustomerById($customer_id);
    
    require_once './views/customers/editForm.php';
}

// Xử lý cập nhật thông tin khách
public function updateCustomer() {
    // Lấy dữ liệu từ form POST
    $customer_id = $_POST['customer_id'] ?? null;
    $booking_id = $_POST['booking_id'] ?? null;
    $full_name = $_POST['full_name'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $birth_year = $_POST['birth_year'] ?? '';
    $id_number = $_POST['id_number'] ?? '';
    $special_request = $_POST['special_request'] ?? '';

    // Kiểm tra dữ liệu đầu vào
    if (!$customer_id || !$booking_id || empty($full_name) || empty($gender) || empty($birth_year) || empty($id_number)) {
        echo "<p style='color:red;padding:1rem'>Thiếu thông tin cần thiết để cập nhật khách hàng.</p>";
        echo "<a href='" . BASE_URL . "?route=/customerBooking&booking_id=$booking_id' style='color:blue'>← Quay lại danh sách</a>";
        return;
    }

    // Gọi model để cập nhật
    $success = $this->model->updateCustomer($customer_id, $full_name, $gender, $birth_year, $id_number, $special_request);

    // Redirect hoặc thông báo
    if ($success) {
        header("Location: " . BASE_URL . "?route=/customerBooking&booking_id=" . $booking_id);
        exit;
    } else {
        echo "<p style='color:red;padding:1rem'>Cập nhật thất bại. Vui lòng thử lại.</p>";
        echo "<a href='" . BASE_URL . "?route=/customerBooking/editCustomer&id=$customer_id&booking_id=$booking_id' style='color:blue'>← Quay lại sửa</a>";
    }
}


    // ✏️ Cập nhật yêu cầu đặc biệt
    public function updateRequest($customer_id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $special_request = $_POST['special_request'];
            $booking_id = $_POST['booking_id'];
            $this->model->updateSpecialRequest($customer_id, $special_request);
            header("Location: " . BASE_URL . "?route=/customerBooking&booking_id=" . $booking_id);
            exit;
        }
    }

    // ☑️ Điểm danh khách
    public function checkIn($customer_id, $booking_id) {
        $this->model->checkInCustomer($customer_id);
        header("Location: " . BASE_URL . "?route=/customerBooking&booking_id=" . $booking_id);
        exit;
    }

    // 📊 Xuất danh sách khách ra Excel
    public function exportExcel($booking_id) {
        
        $customers = $this->model->getCustomersByBooking($booking_id);

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=booking_{$booking_id}_customers.xls");

        echo "STT\tHọ tên\tGiới tính\tNăm sinh\tSố giấy tờ\tYêu cầu đặc biệt\tĐiểm danh\n";
        foreach ($customers as $i => $c) {
            echo ($i+1) . "\t" .
                 $c['full_name'] . "\t" .
                 $c['gender'] . "\t" .
                 $c['birth_year'] . "\t" .
                 $c['id_number'] . "\t" .
                 $c['special_request'] . "\t" .
                 ($c['checked_in'] ? 'Đã điểm danh' : '') . "\n";
        }
        exit;
    }

    
}

?>
