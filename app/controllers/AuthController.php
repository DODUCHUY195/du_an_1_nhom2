<?php
class AuthController extends BaseController
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $pw = $_POST['password'] ?? '';
            $u = new User();
            $user = $u->findByEmail($email);
            if ($user && password_verify($pw, $user['password'])) {
                unset($user['password']); // không lưu password vào session
                $_SESSION['user'] = $user;
                $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Đăng nhập thành công'];
                $this->redirect('/tours');
                return;
            } else {
                $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Email hoặc mật khẩu không đúng'];
            }
        }
        $this->render('auth/login');
    }

    public function logout()
    {
        session_destroy();
        $this->redirect('/');
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['full_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $password = $_POST['password'] ?? '';

            // validate cơ bản
            if ($name === '' || $email === '' || $password === '') {
                $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Vui lòng điền đầy đủ thông tin bắt buộc'];
                $this->render('auth/register');
                return;
            }

            $u = new User();
            if ($u->findByEmail($email)) {
                $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Email đã được sử dụng'];
                $this->render('auth/register');
                return;
            }

            $pwHash = password_hash($password, PASSWORD_DEFAULT);
            $ok = $u->create([
                ':n' => $name,
                ':e' => $email,
                ':p' => $phone,
                ':pw' => $pwHash,
                ':r' => 'user',
                ':a' => 1
            ]);

            if ($ok) {
                $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Đăng ký thành công'];
                $this->redirect('/login');
                return;
            } else {
                $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Lỗi tạo tài khoản'];
            }
        }
        $this->render('auth/register');
    }
}
