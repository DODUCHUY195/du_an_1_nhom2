<?php
class HomeController extends BaseController {
    public function home() {
        // truyền dữ liệu nếu cần
        $data = [
            'pageTitle' => 'Trang quản trị',
            // 'user' => $currentUser,
        ];
        $this->render('admin/home', $data);
    }
}
// không đóng tag PHP ở cuối file
