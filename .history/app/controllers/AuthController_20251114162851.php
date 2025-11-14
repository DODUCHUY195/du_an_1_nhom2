<?php
class AuthController extends BaseController
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $pw = $_POST['password'] ?? '';
            $u = new User();
            $user = $u->findByEmail($email);
            if ($user && password_verify($pw, $user['password'])) {
                $_SESSION['user'] = $user;
                $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Đăng nhập thành công'];
                $this->redirect('/tours');
            } else {
                $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Email hoặc mật khẩu không đúng'];
            }
        }
        $this->render('views/auth/login');
    }
    public function logout()
    {
        session_destroy();
        $this->redirect('/');
    }
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['full_name'];
            $email = $_POST['email'];
            $pw = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $u = new User();
            $ok = $u->create([':n' => $name, ':e' => $email, ':p' => $_POST['phone'], ':pw' => $pw, ':r' => 'customer', ':a' => 1]);
            if ($ok) {
                $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Đăng ký thành công'];
                $this->redirect('/login');
            } else {
                $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Lỗi tạo tài khoản'];
            }
        }
        $this->render('views/auth/register');
    }
}
