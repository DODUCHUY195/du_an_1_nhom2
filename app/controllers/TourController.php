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
        // Get current page number
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page); // Ensure page is at least 1
        
        // Items per page
        $itemsPerPage = 5;
        
        // Get filter parameters
        $category_id = $_GET['category_id'] ?? '';
        $search = $_GET['search'] ?? '';
        $price_range = $_GET['price_range'] ?? '';
        $status = $_GET['status'] ?? '';
        
        // Parse price range
        $min_price = '';
        $max_price = '';
        if (!empty($price_range)) {
            $range = explode('-', $price_range);
            if (count($range) == 2) {
                $min_price = $range[0];
                $max_price = $range[1];
            }
        }
        
        // Get tours with pagination and filters
        $result = $this->modelTour->getToursWithFilters($category_id, $search, $min_price, $max_price, $status, $page, $itemsPerPage);
        $listTour = $result['tours'];
        $totalTours = $result['total'];
        $totalPages = ceil($totalTours / $itemsPerPage);
        
        require_once APP_PATH . '/views/tours/index.php';
    }

    // form thêm tour
    public function addForm()
    {
        // Tạo mã tour tự động
        $tourCode = $this->modelTour->generateTourCode();
        
        // Lấy danh sách category
        $categoryModel = new Category();
        $categories = $categoryModel->getAllCategory();
        
        require_once APP_PATH . '/views/tours/addForm.php';
    }

    // xử lý thêm tour
    public function postAdd()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ten = $_POST['tour_name'];
            $gia = $_POST['price'];
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $mota = $_POST['description'];
            $status = $_POST['status'];
            $category_id = $_POST['category_id'] ?? null;
            
            // Kiểm tra ngày bắt đầu không nhỏ hơn ngày hiện tại
            $current_date = date('Y-m-d');
            if (!$this->modelTour->validateTourDates($start_date, $current_date)) {
                // Hiển thị thông báo lỗi và quay lại form
                echo "<script>alert('Ngày bắt đầu không hợp lệ! Ngày bắt đầu phải lớn hơn hoặc bằng ngày hiện tại.'); window.history.back();</script>";
                return;
            }
            
            // Kiểm tra ngày kết thúc không nhỏ hơn ngày bắt đầu
            if ($end_date && $start_date) {
                $start = new DateTime($start_date);
                $end = new DateTime($end_date);
                if ($end < $start) {
                    echo "<script>alert('Ngày kết thúc không hợp lệ! Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu.'); window.history.back();</script>";
                    return;
                }
            }
            
            // Tạo mã tour tự động
            $code = $this->modelTour->generateTourCode();

            // Xử lý upload hình ảnh
            $image = null;
            if (!empty($_FILES['image']['name'])) {
                $uploadDir = '../uploads/tours/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
                $targetFilePath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                    $image = 'uploads/tours/' . $fileName;
                }
            }

            $this->modelTour->insertTour($ten, $code, $gia, $start_date, $end_date, $mota, $status, $category_id, $image);

            header("Location: " . BASE_URL . "?route=/tours");
            exit();
        }
    }

    // form sửa tour
    public function editForm()
    {
        $id = $_GET['tour_id'];
        error_log("Loading edit form for tour ID: " . $id);
        $tour = $this->modelTour->getDetailTour($id);
        error_log("Tour data loaded: " . print_r($tour, true));
        
        // Lấy danh sách category
        $categoryModel = new Category();
        $categories = $categoryModel->getAllCategory();
        error_log("Categories loaded: " . print_r($categories, true));
        
        // Lấy giá theo mùa và hình ảnh
        $prices = $this->modelTour->getTourPrices($id);
        $images = $this->modelTour->getTourImages($id);
        error_log("Prices loaded: " . print_r($prices, true));
        error_log("Images loaded: " . print_r($images, true));
        
        require_once APP_PATH . '/views/tours/editForm.php';
    }

    // xử lý sửa tour
    public function postEdit()
    {
        error_log("postEdit method called");
        error_log("Request method: " . $_SERVER['REQUEST_METHOD']);
        error_log("POST data: " . print_r($_POST, true));
        error_log("FILES data: " . print_r($_FILES, true));
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['tour_id'];
            $ten = $_POST['tour_name'];
            $gia = $_POST['price'];
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $mo_ta = $_POST['description'];
            $trang_thai = $_POST['status'];
            $category_id = $_POST['category_id'] ?? null;
            
            error_log("Processing tour update for ID: " . $id);
            
            // Kiểm tra ngày bắt đầu không nhỏ hơn ngày tạo
            $tour = $this->modelTour->getDetailTour($id);
            $created_at = $tour['created_at'];
            
            if (!$this->modelTour->validateTourDates($start_date, $created_at)) {
                error_log("Date validation failed for tour ID: " . $id);
                // Hiển thị thông báo lỗi và quay lại form
                echo "<script>alert('Ngày bắt đầu không hợp lệ! Ngày bắt đầu phải lớn hơn hoặc bằng ngày tạo tour.'); window.history.back();</script>";
                return;
            }
            
            // Kiểm tra ngày kết thúc không nhỏ hơn ngày bắt đầu
            if ($end_date && $start_date) {
                $start = new DateTime($start_date);
                $end = new DateTime($end_date);
                if ($end < $start) {
                    error_log("End date validation failed for tour ID: " . $id);
                    echo "<script>alert('Ngày kết thúc không hợp lệ! Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu.'); window.history.back();</script>";
                    return;
                }
            }
            
            // Giữ nguyên mã tour cũ
            $code = $tour['tour_code'];
            
            // Xử lý upload hình ảnh
            $image = $_POST['current_image'] ?? null;
            if (!empty($_FILES['image']['name'])) {
                $uploadDir = '../uploads/tours/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
                $targetFilePath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                    $image = 'uploads/tours/' . $fileName;
                    error_log("Image uploaded: " . $image);
                }
            }

            $result = $this->modelTour->updateTour($id, $ten, $gia, $start_date, $end_date, $mo_ta, $trang_thai, $category_id, $image);
            error_log("Update tour result: " . ($result ? "success" : "failed"));

            header("Location: " . BASE_URL . "?route=/tours");
            exit();
        }
    }

    // chi tiết tour
    public function detail()
    {
        $id = $_GET['tour_id'];
        $tour = $this->modelTour->getDetailTour($id);
        
        // Get related information
        $scheduleModel = new Schedule();
        $schedules = $scheduleModel->getByTourId($id);
        $scheduleCount = count($schedules);
        
        // For demo purposes, we'll set some dummy values
        $bookingCount = 0;
        $totalRevenue = 0;
        
        foreach ($schedules as $schedule) {
            // In a real implementation, you would count actual bookings
            $bookingCount += $schedule['seats_booked'];
            $totalRevenue += $schedule['seats_booked'] * $tour['price'];
        }
        
        require_once APP_PATH . '/views/tours/detail.php';
    }

    // cập nhật trạng thái tour
    public function updateStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $tour_id = $_POST['tour_id'];
            $status = $_POST['status'];
            
            try {
                $this->modelTour->updateTourStatus($tour_id, $status);
                $_SESSION['success_message'] = 'Cập nhật trạng thái tour thành công!';
            } catch (Exception $e) {
                $_SESSION['error_message'] = 'Lỗi: ' . $e->getMessage();
            }
            
            header("Location: " . BASE_URL . "?route=/tours/detail&tour_id=" . $tour_id);
            exit();
        }
    }

    // xóa tour11
    public function delete()
    {
        $id = $_GET['tour_id'];
        $this->modelTour->deleteTour($id);
        header("Location: " . BASE_URL . "?route=/tours");
        exit();
    }
}