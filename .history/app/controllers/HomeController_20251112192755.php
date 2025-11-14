<?php
class HomeController extends BaseController {
    public function home() {
        $data = [
            'pageTitle' => 'Trang quản trị'
        ];
        $this->render('views/home', $data);
    }

    public function manager() {
        $data = [
            'pageTitle' => 'Trang quản lý'
        ];
        $this->render('views/manager', $data);
    }

     public function user() {
        $data = [
            'pageTitle' => 'Trang quản lý'
        ];
        $this->render('views/user', $data);
    }
}
