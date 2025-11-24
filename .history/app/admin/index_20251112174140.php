<?php
session_start();
define('APP_PATH', dirname(__DIR__));

// ----- require các file chung -----
require_once APP_PATH . '/commons/env.php';
require_once APP_PATH . '/commons/function.php';

// ----- require tất cả models -----
require_once APP_PATH . '/models/BaseModel.php';
require_once APP_PATH . '/models/Booking.php';
require_once APP_PATH . '/models/Category.php';
require_once APP_PATH . '/models/Guide.php';
require_once APP_PATH . '/models/Schedule.php';
require_once APP_PATH . '/models/Tour.php';
require_once APP_PATH . '/models/User.php';

// ----- require tất cả controllers -----
require_once APP_PATH . '/controllers/BaseController.php';
require_once APP_PATH . '/controllers/AdminBookingController.php';
require_once APP_PATH . '/controllers/AuthController.php';
require_once APP_PATH . '/controllers/BookingController.php';
require_once APP_PATH . '/controllers/CategoryController.php';
require_once APP_PATH . '/controllers/GuideController.php';
require_once APP_PATH . '/controllers/HomeController.php';
require_once APP_PATH . '/controllers/ScheduleController.php';
require_once APP_PATH . '/controllers/TourController.php';

// ====== ROUTE: lấy từ query string, fallback /home ======
// gọi url như: index.php?route=/tours/create
$route = $_GET['route'] ?? '/home';
if ($route === '/') $route = '/home';

// Hàm helper: an toàn gọi controller->method
function callController($class, $method = 'index', $params = []) {
    if (!class_exists($class)) {
        echo "Controller class '{$class}' không tồn tại."; return;
    }
    $c = new $class();
    if (!method_exists($c, $method)) {
        echo "Method '{$method}' không tồn tại trong {$class}."; return;
    }
    call_user_func_array([$c, $method], $params);
}

// ====== Router ======
switch ($route) {
    case '/home':
        callController('HomeController', 'home');
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
    case '/tours/create':
        callController('TourController', 'create');
        break;
    case '/tours/edit':
        // lấy id từ GET: index.php?route=/tours/edit&id=123
        $id = $_GET['id'] ?? null;
        callController('TourController', 'edit', [$id]);
        break;

    case '/schedules':
        callController('ScheduleController', 'index');
        break;
    case '/schedules/create':
        callController('ScheduleController', 'create');
        break;
    case '/schedules/edit':
        $id = $_GET['id'] ?? null;
        callController('ScheduleController', 'edit', [$id]);
        break;

    case '/guides':
        callController('GuideController', 'index');
        break;
    case '/guides/create':
        callController('GuideController', 'create');
        break;
    case '/guides/edit':
        $id = $_GET['id'] ?? null;
        callController('GuideController', 'edit', [$id]);
        break;

    case '/categories':
        callController('CategoryController', 'index');
        break;
    case '/categories/create':
        callController('CategoryController', 'create');
        break;

    case '/bookings':
        callController('BookingController', 'index');
        break;
    case '/bookings/create':
        callController('BookingController', 'create');
        break;

    case '/admin/bookings':
        callController('AdminBookingController', 'index');
        break;
    case '/admin/bookings/update':
        // update status thường nên dùng POST; lấy id/status từ POST
        callController('AdminBookingController', 'updateStatus', [$_POST ?? []]);
        break;

    default:
        echo "<h1>Welcome</h1><p><a href='?route=/tours'>Tours</a></p>";
}
