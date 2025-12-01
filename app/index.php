
<?php
// DEV: hiển thị lỗi tạm thời để debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

// start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// định nghĩa APP_PATH trỏ tới /app
define('APP_PATH', __DIR__); // nếu file đang ở /app/admin thì APP_PATH -> .../du_an_11/app

// require các file chung
require_once APP_PATH . '/commons/env.php';
require_once APP_PATH . '/commons/function.php';


spl_autoload_register(function ($class) {
    include 'models/' . $class . '.php';
});



// ----- require tất cả models -----
$models = [
    APP_PATH . '/models/GuideAssignment.php',
    APP_PATH . '/models/BaseModel.php',
    APP_PATH . '/models/Daily_log.php',
    APP_PATH . '/models/Booking.php',
    APP_PATH . '/models/BookingCustomer.php',
    APP_PATH . '/models/Category.php',
    APP_PATH . '/models/Guide.php',
    APP_PATH . '/models/Schedule.php',
    APP_PATH . '/models/Tour.php',
    APP_PATH . '/models/Account.php',
    APP_PATH . '/models/Vehicle.php',
    APP_PATH . '/models/DashboardModel.php',
];

foreach ($models as $mFile) {
    if (!file_exists($mFile)) {
        die("Required model not found: $mFile");
    }
    require_once $mFile;
}

// ----- require tất cả controllers -----
$controllers = [
    APP_PATH . '/controllers/AdminController.php',
    APP_PATH . '/controllers/AdminBookingController.php',
    APP_PATH . '/controllers/BookingCustomerController.php',
    APP_PATH . '/controllers/AccountController.php',

    APP_PATH . '/controllers/CustomerController.php',
    APP_PATH . '/controllers/BookingController.php',
    APP_PATH . '/controllers/AuthController.php',
    APP_PATH . '/controllers/BookingController.php',
    APP_PATH . '/controllers/CategoryController.php',
    APP_PATH . '/controllers/GuideController.php',
    APP_PATH . '/controllers/HomeController.php',
    APP_PATH . '/controllers/ScheduleController.php',
    APP_PATH . '/controllers/TourController.php',
];

foreach ($controllers as $cFile) {
    if (!file_exists($cFile)) {
        die("Required controller not found: $cFile");
    }
    require_once $cFile;
}

// tiếp phần route...


// ====== ROUTE: lấy từ query string, fallback /home ======
// gọi url như: index.php?route=/tours/create

// $isAdmin = isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';



$route = $_GET['route'] ?? '/';

// if (str_starts_with($route, '/admin') && !$isAdmin) {
//     echo "Bạn không có quyền truy cập trang quản trị.";
//     exit;
// }

// Hàm helper: an toàn gọi controller->method
function callController($class, $method = 'index', $params = [])
{
    if (!class_exists($class)) {
        echo "Controller class '{$class}' không tồn tại.";
        return;
    }
    $c = new $class();
    if (!method_exists($c, $method)) {
        echo "Method '{$method}' không tồn tại trong {$class}.";
        return;
    }
    call_user_func_array([$c, $method], $params);
}


if ($route === null) {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'); // /du_an_11/app/admin
    $route = '/' . trim(substr($uri, strlen($base)), '/');
}
$route = $route === '' ? '/' : $route;

$role = $_SESSION['user']['role'] ?? 'Guest';


if (str_starts_with($route, '/login') && $role !== 'Guest') {
    echo "Bạn đã đăng nhập.";
    exit;
}
checkPermission($route, $role);

$db = connectDB(); // dùng hàm trong function.php
$auth = new AuthController($db);
// ====== Router ======
switch ($route) {

    // Trang dành riêng cho Admin tổng
        case '/admin':
        callController('AdminController', 'dashboard');
        break;

    case '/admin/exportExcel':
        callController('AdminController', 'exportExcel');
        break;

    case '/admin/exportPdf':
        callController('AdminController', 'exportPdf');
        break;

    // Trang chủ
    case '/':
        callController('HomeController', 'home');
        break;


    case '/customerBooking':
        callController('BookingCustomerController', 'index', [$_GET['booking_id'] ?? '']);
        break;

    // ➕ Form thêm khách
    case '/customerBooking/create':
        callController('BookingCustomerController', 'createForm', [$_GET['booking_id'] ?? '']);
        break;

    // ✅ Xử lý thêm khách
    case '/customerBooking/postCreate':
        callController('BookingCustomerController', 'postCreate', [$_GET['booking_id'] ?? '']);
        break;

    // ✏️ Cập nhật yêu cầu đặc biệt
    case '/customerBooking/updateRequest':
        callController('BookingCustomerController', 'updateRequest', [$_GET['customer_id'] ?? '']);
        break;

    // ☑️ Điểm danh khách
    case '/customerBooking/checkIn':
        callController('BookingCustomerController', 'checkIn', [$_GET['customer_id'] ?? '', $_GET['booking_id'] ?? '']);
        break;
    // Hiển thị form sửa khách
    case '/customerBooking/editCustomer':

        callController('BookingCustomerController', 'editCustomer', [$_GET['customer_id'] ?? '']);
        break;

    // Xử lý cập nhật sau khi sửa
    case '/customerBooking/updateCustomer':
        callController('BookingCustomerController', 'updateCustomer', [$_GET['customer_id'] ?? '']);
        break;

    // 📊 Xuất danh sách khách ra Excel
    case '/customerBooking/exportExcel':
        callController('BookingCustomerController', 'exportExcel', [$_GET['booking_id'] ?? '']);
        break;
    case '/customerBooking/select':
        callController('BookingCustomerController', 'selectBooking');
        break;


    // case '/':
    //     callController('HomeController', 'home');
    //     break;
    case '/manager':
        callController('HomeController', 'manager');
        break;
    // Đăng nhập / đăng ký / đăng xuất
    case '/login':
        require './views/auth/login.php';
        break;

    case '/postLogin':
        $auth->login($_POST['email'], $_POST['password']);
        break;

    case '/register':
        require './views/auth/register.php';
        break;

    case '/postRegister':
        $auth->register($_POST);
        break;

    case '/logout':
        $auth->logout();
        break;

    // Quản lý Tài khoản & Phân quyền
    case '/accounts':
        callController('AccountController', 'index');
        break;

    case '/accounts/create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            callController('AccountController', 'create', [$_POST]);
        } else {
            require './views/account/create.php';
        }
        break;

    case '/accounts/edit':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            callController('AccountController', 'edit', [$_GET['id'] ?? '', $_POST]);
        } else {
            callController('AccountController', 'editForm', [$_GET['id'] ?? '']);
        }
        break;

    case '/accounts/toggle':
        callController('AccountController', 'toggle', [$_GET['id'] ?? '']);
        break;

    case '/accounts/reset':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            callController('AccountController', 'resetPassword', [$_GET['id'] ?? '', $_POST['new_password']]);
        } else {
            require './views/account/reset.php';
        }
        break;

    case '/accounts/assignRole':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            callController('AccountController', 'assignRole', [$_GET['id'] ?? '', $_POST['role']]);
        } else {
            require './views/account/assignRole.php';
        }
        break;


    case '/tours':
        callController('TourController', 'index');
        break;
    case '/tours/addForm':
        callController('TourController', 'addForm');
        break;
    case '/tours/postAdd':
        callController('TourController', 'postAdd');
        break;
    case '/tours/editForm':
        callController('TourController', 'editForm');
        break;
    case '/tours/postEdit':
        callController('TourController', 'postEdit');
        break;
    case '/tours/delete':
        $tour_id = $_GET['tour_id'] ?? null;
        if ($tour_id) {
            callController('TourController', 'delete', [$tour_id]);
        }
        break;
        
    case '/tours/detail':
        callController('TourController', 'detail');
        break;
        
    case '/tours/updateStatus':
        callController('TourController', 'updateStatus');
        break;

   // ============================
    // ROUTE SCHEDULE (LỊCH KHỞI HÀNH)
    // ============================

    // 1) CRUD LỊCH TRÌNH
    case '/schedules':
        $controller = new ScheduleController();
        $controller->index();
        break;
        
    case '/schedules/operationDashboard':
        $controller = new ScheduleController();
        $controller->operationDashboard();
        break;
        
    case '/schedules/detail':
        $controller = new ScheduleController();
        $controller->detail();
        break;
        
    case '/schedules/addForm':
        callController('ScheduleController', 'addForm');
        break;

    case '/schedules/postAdd':
        callController('ScheduleController', 'postAdd');
        break;

    case '/schedules/editForm':
        callController('ScheduleController', 'editForm');
        break;

    case '/schedules/postEdit':
        callController('ScheduleController', 'postEdit');
        break;

    case '/schedules/delete':
        callController('ScheduleController', 'delete');
        break;
    // 2) CHI TIẾT TOUR – ĐIỀU HÀNH
    case '/schedules/detail':
        callController('ScheduleController', 'detail');
        break;
    // 3) PHÂN CÔNG HDV
    case '/schedules/assignGuideForm':
        callController('ScheduleController', 'assignGuideForm');
        break;

    case '/schedules/postAssignGuide':
        callController('ScheduleController', 'postAssignGuide');
        break;

    case '/schedules/removeGuide':
        callController('ScheduleController', 'removeGuide');
        break;

    // New route for assigning guide from schedule listing
    case '/schedules/assignGuideFromList':
        callController('ScheduleController', 'assignGuideFromList');
        break;

    // 4) NHẬT KÝ
    case '/schedules/dailyLog':
        callController('ScheduleController', 'dailyLog');
        break;

    case '/schedules/addDailyLog':
        callController('ScheduleController', 'addDailyLog');
        break;

    case '/guides':
        callController('GuideController', 'index');
        break;
    case '/guides/create':
        callController('GuideController', 'create');
        break;
    case '/guides/edit':
        callController('GuideController', 'edit', [$_GET['id'] ?? null]);
        break;
    case '/guides/delete':
        callController('GuideController', 'delete', [$_GET['id'] ?? null]);
        break;
    case '/guides/assign':
        callController('GuideController', 'assign', [$_GET['id'] ?? null]);
        break;

    case '/categories':
        callController('CategoryController', 'index');
        break;
    case '/categories/delete':
        callController('CategoryController', 'delete');
        break;
    case '/categories/addForm':
        callController('CategoryController', 'addForm');
        break;
    case '/categories/postAdd':
        callController('CategoryController', 'postAdd');
        break;
    case '/categories/postEdit':
        callController('CategoryController', 'postEdit');
        break;
    case '/categories/editForm':
        callController('CategoryController', 'editForm');
        break;
    case '/bookings':
        callController('AdminBookingController', 'index');
        break;

    case '/bookings/create':
        callController('AdminBookingController', 'create');
        break;

    case '/bookings/update':
        callController('AdminBookingController', 'updateStatus', [$_POST ?? []]);
        break;

    case '/bookings/cancel':
        callController('AdminBookingController', 'cancel', [$_GET['booking_id'] ?? null]);
        break;

    case '/booking/customer':
        callController('BookingController', 'customerCreate');
        break;

    case '/booking/customer/store':
        callController('BookingController', 'storeFromCustomer', [$_POST ?? []]);
        break;

    default:
        echo "<h1>Welcome</h1><p><a href='?route=/tours'>Tours</a></p>";
}
