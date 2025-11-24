<?php
class HomeController
{
    public function home()
    {
        require_once './app/views/home.php';
    }

    public function manager()
    {
        $data = [
            'pageTitle' => 'Trang quản lý'
        ];
        $this->render('views/manager', $data);
    }

    public function user()
    {
        $data = [
            'pageTitle' => 'Trang người dùng'
        ];
        $this->render('views/user', $data);
    }
}
