<?php
class HomeController extends BaseController {
    public function home() {
        $data = [
            'pageTitle' => 'Trang quản trị'
        ];
        $this->render('admin/views/home', $data);
    }

    public function manager() {
        $data = [
            'pageTitle' => 'Trang quản lý'
        ];
        $this->render('admin/views/home', $data);
    }
}
