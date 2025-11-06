<?php
/**
 * File chứa các hàm tiện ích (helper) dùng chung trong dự án.
 * Các hàm này KHÔNG thuộc class nào, có thể gọi trực tiếp ở bất kỳ đâu.
 */

/**
 * Hàm debug nhanh — in mảng hoặc object dễ nhìn.
 */
function dd($data) {
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    exit;
}

/**
 * Hàm chuyển hướng nhanh (redirect)
 */
function redirect($url) {
    header("Location: {$url}");
    exit;
}

/**
 * Hàm lấy base URL từ config (nếu có)
 */
function base_url($path = '') {
    $config = require __DIR__ . '/../config/config.php';
    $base = $config['base_url'] ?? '';
    return rtrim($base, '/') . '/' . ltrim($path, '/');
}

/**
 * Hàm lọc dữ liệu đầu vào (tránh XSS cơ bản)
 */
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
