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

// ----- require tất cả models -----
$models = [
    APP_PATH . '/models/BaseModel.php',
    APP_PATH . '/models/Booking.php',
    APP_PATH . '/models/Category.php',
    APP_PATH . '/models/Daily_log.php',
    APP_PATH . '/models/Guide.php',
    APP_PATH . '/models/GuideAssignment.php',
    APP_PATH . '/models/Schedule.php',
    APP_PATH . '/models/Tour.php',
    APP_PATH . '/models/User.php',
];

foreach ($models as $mFile) {
    if (!file_exists($mFile)) {
        die("Required model not found: $mFile");
    }
    require_once $mFile;
}

// ----- require tất cả controllers -----
$controllers = [
    APP_PATH . '/controllers/BookingController.php',
    APP_PATH . '/controllers/AuthController.php',
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

$route = $_GET['route'] ?? null;
if ($route === null) {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'); // /du_an_11/app/admin
    $route = '/' . trim(substr($uri, strlen($base)), '/');
}
$route = $route === '' ? '/home' : $route;

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

// ====== Router ======
switch ($route) {
    case '/user':
        callController('HomeController', 'user');
        break;
    case '/':
        callController('HomeController', 'home');
        break;
    case '/manager':
        callController('HomeController', 'manager');
        break;
    case '/login':
        callController('AuthController', 'login');
        break;
    case '/logout':
        callController('AuthController', 'logout');
        break;
    case '/register':
        callController('AuthController', 'register');
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
    // ROUTE CATEGORY
    // ============================
    case '/categories':
        callController('CategoryController', 'index');
        break;
    case '/categories/addForm':
        callController('CategoryController', 'addForm');
        break;
    case '/categories/postAdd':
        callController('CategoryController', 'postAdd');
        break;
    case '/categories/editForm':
        callController('CategoryController', 'editForm');
        break;
    case '/categories/postEdit':
        callController('CategoryController', 'postEdit');
        break;
    case '/categories/delete':
        callController('CategoryController', 'delete');
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


    // 4) NHẬT KÝ
    case '/schedules/dailyLog':
        callController('ScheduleController', 'dailyLog');
        break;

    case '/schedules/addDailyLog':
        callController('ScheduleController', 'addDailyLog');
        break;
        
    default:
        // 404 - Page not found
        http_response_code(404);
        echo "404 - Page not found";
        break;
}