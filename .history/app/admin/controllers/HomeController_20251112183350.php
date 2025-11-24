<?php
class HomeController extends BaseController {
    public function home() {
        // truyền dữ liệu nếu cần
        require_once APP_PATH . '/views/admin/home.php';
    }
}
// không đóng tag PHP ở cuối file
