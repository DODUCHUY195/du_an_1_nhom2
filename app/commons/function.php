<?php

function connectDB(){
    $host = DB_HOST;
    $port = DB_PORT;
    $dbname = DB_NAME;
    try{
        $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", DB_USERNAME, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
        return $conn;
    }catch(PDOException $e){
        echo("Connection Failed:".$e->getMessage());
        return null;
    }
}

function uploadfile($file, $uploadDir){
    if ($file['error'] !== UPLOAD_ERR_OK) return false;
    $fileName = basename($file['name']);
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','gif'];
    if (!in_array($ext, $allowed)) return false;
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    $newFileName = uniqid() . '.' . $ext;
    $dest = rtrim($uploadDir, '/') . '/' . $newFileName;
    if (move_uploaded_file($file['tmp_name'], $dest)) return $newFileName;
    return false;
}

function deletefile($filePath){
    if(file_exists($filePath)) { unlink($filePath); return true; }
    return false;
}

function active($route) {
    $current = $_GET['route'] ?? '/';
    return $current === $route 
        ? "font-bold text-blue-600 bg-blue-100" 
        : "text-slate-600 hover:text-blue-600";
}

function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    function get($key) {
        return $_SESSION[$key] ?? null;
    }
 function destroy() {
        session_start();
        session_destroy();
    }

    function checkPermission($route, $role)
{
    $public = ['/login', '/register', '/postLogin', '/postRegister'];
    if (in_array($route, $public)) return;
    if (str_starts_with($route, '/admin') && $role !== 'AdminTong') {
        echo "⛔ Chỉ Admin tổng mới được truy cập.";
        exit;
    }

    if (str_starts_with($route, '/manager') && !in_array($role, ['AdminTong', 'QuanLy'])) {
        echo "⛔ Chỉ Admin tổng hoặc Quản lý mới được truy cập.";
        exit;
    }

    if (str_starts_with($route, '/') && !in_array($role, ['AdminTong', 'QuanLy', 'Customer'])) {
        echo "⛔ Bạn chưa đăng nhập hoặc không có quyền.";
        exit;
    }
}

?>
