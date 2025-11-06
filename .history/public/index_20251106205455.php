<?php
// public/index.php
declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';      // nếu dùng composer
$config = require __DIR__ . '/../config/config.php';

use Core\Request, Core\Router;

$request = Request::capture();
$router  = new Router($request);

// load routes
require __DIR__ . '/../routes/web.php';

// dispatch
$router->dispatch();
