<?php
// Start session and autoload minimal
session_start();
require_once __DIR__ . '/../app/helpers/db.php';
require_once __DIR__ . '/../app/helpers/session.php';

// Autoload controllers/models simple
spl_autoload_register(function($class){
    $paths = [__DIR__ . '/../app/controllers/', __DIR__ . '/../app/models/'];
    foreach($paths as $p){
        $f = $p . $class . '.php';
        if(file_exists($f)) require_once $f;
    }
});

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$route = '/' . trim(substr($uri, strlen($base)), '/');
if($route === '/') $route = '/home';

// Simple routing
switch($route){
    case '/login':
        $c = new AuthController(); $c->login(); break;
    case '/logout':
        $c = new AuthController(); $c->logout(); break;
    case '/register':
        $c = new AuthController(); $c->register(); break;
    case '/tours':
        $c = new TourController(); $c->index(); break;
    case '/tours/create':
        $c = new TourController(); $c->create(); break;
    case (preg_match('#^/tours/edit/(\\d+)$#', $route, $m) ? true : false):
        $c = new TourController(); $c->edit($m[1]); break;
    case '/bookings':
        $c = new BookingController(); $c->index(); break;
    default:
        echo "<h1>Welcome</h1><p><a href='/tours'>Tours</a></p>";
}
