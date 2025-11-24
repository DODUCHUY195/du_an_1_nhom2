<?php
session_start();
define('APP_PATH', dirname(__DIR__));
// ----- require các file chung -----
require_once APP_PATH . '/commons/env.php';
require_once APP_PATH . '/commons/function.php';

// ----- require tất cả models (theo danh sách của bạn) -----
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
