<?php
class AuthController
{
    private $accountModel;

    public function __construct($db)
    {
        $this->accountModel = new Account($db);
    }

    public function login($email, $password)
    {
        $user = $this->accountModel->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;

            switch ($user['role']) {
                case 'AdminTong':
                    header("Location: " . BASE_URL . "index.php?route=/admin");
                    break;
                case 'QuanLy':
                    header("Location: " . BASE_URL . "index.php?route=/manager");
                    break;
                default:
                    header("Location: " . BASE_URL . "index.php?route=/");
            }
            exit;
        } else {
            echo "<p style='color:red'>❌ Sai tài khoản hoặc mật khẩu!</p>";
        }
    }

    public function register($data)
    {
        if ($this->accountModel->createSignUp($data)) {
            echo "<p style='color:green'>✅ Đăng ký thành công! <a href='" . BASE_URL . "index.php?route=/login'>Đăng nhập ngay</a></p>";
        } else {
            echo "<p style='color:red'>❌ Đăng ký thất bại!</p>";
        }
    }

    public function logout()
    {
        session_destroy();
        header("Location: " . BASE_URL . "index.php?route=/login");
        exit;
    }
}
