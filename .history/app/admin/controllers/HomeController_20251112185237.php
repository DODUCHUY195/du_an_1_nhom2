<?php
class HomeController extends BaseController {
    public function home() {
        $data = [
            'pageTitle' => 'Trang quản trị'
        ];
        $this->render('admin/views/admin/home', $data);
    }
}
