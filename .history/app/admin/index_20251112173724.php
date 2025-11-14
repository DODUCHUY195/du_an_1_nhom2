<?php
session_start();
define('APP_PATH', dirname(__DIR__));
require_once dirname(__DIR__) . '/commons/env.php';
require_once dirname(__DIR__) . '/commons/function.php';

// Autoload minimal
spl_autoload_register(function ($class) {
    $baseDir = dirname(__DIR__); // đi lên 1 cấp từ thư mục hiện tại
    $paths = [
        $baseDir . '/controllers/',
        $baseDir . '/models/',
    ];
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$route = '/' . trim(substr($uri, strlen($base)), '/');
if ($route === '/') $route = '/home';

switch ($route) {
    case '/home':
        $c = new HomeController();
        $c->home();
        break;
    case '/login':
        $c = new AuthController();
        $c->login();
        break;
    case '/logout':
        $c = new AuthController();
        $c->logout();
        break;
    case '/register':
        $c = new AuthController();
        $c->register();
        break;
    case '/tours':
        $c = new TourController();
        $c->index();
        break;
    case '/tours/create':
        $c = new TourController();
        $c->create();
        break;
    case '/tours/edit':
        $c = new TourController();
        $c->edit();
        break;

    case '/schedules':
        $c = new ScheduleController();
        $c->index();
        break;
    case '/schedules/create':
        $c = new ScheduleController();
        $c->create();
        break;
     case '/schedules/edit':
        $c = new ScheduleController();
        $c->edit($_POST['schedule_id '] ?? null );
        break;
    case '/guides':
        $c = new GuideController();
        $c->index();
        break;
    case '/guides/create':
        $c = new GuideController();
        $c->create();
        break;
    case '/guides/edit':
        $c = new GuideController();
        $c->edit($_POST['guide_id  '] ?? null );
        break;
    case '/categories':
        $c = new CategoryController();
        $c->index();
        break;
    case '/categories/create':
        $c = new CategoryController();
        $c->create();
        break;
    case '/bookings':
        $c = new BookingController();
        $c->index();
        break;
    case '/bookings/create':
        $c = new BookingController();
        $c->create();
        break;
    case '/admin/bookings':
        $c = new AdminBookingController();
        $c->index();
        break;
    case '/admin/bookings/update':
        $c = new AdminBookingController();
        $c->updateStatus();
        break;
    default:
        echo "<h1>Welcome</h1><p><a href='/tours'>Tours</a></p>";
}
