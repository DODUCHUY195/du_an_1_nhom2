
<?php
// DEV: hiển thị lỗi tạm thời để debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

// start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// định nghĩa APP_PATH trỏ tới /app
define('APP_PATH', __DIR__);// nếu file đang ở /app/admin thì APP_PATH -> .../du_an_11/app

// require các file chung
require_once APP_PATH . '/commons/env.php';
require_once APP_PATH . '/commons/function.php';





// ----- require tất cả models -----
$models = [
    APP_PATH . '/models/BaseModel.php',
    APP_PATH . '/models/Booking.php',
    APP_PATH . '/models/Category.php',
    APP_PATH . '/models/Guide.php',
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
    APP_PATH . '/controllers/BaseController.php',
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

$route = $_GET['route'] ?? null;
if ($route === null) {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'); // /du_an_11/app/admin
    $route = '/' . trim(substr($uri, strlen($base)), '/');
}
$route = $route === '' ? '/home' : $route;

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
    case '/user':
        callController('HomeController', 'user');
        break;
    case '/home':
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
