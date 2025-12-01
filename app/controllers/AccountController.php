<?php
class AccountController
{
    private $accountModel;

    public function __construct()
    {
        $this->accountModel = new Account();
    }

    public function index()
    {
        $accounts = $this->accountModel->getAll();
        require 'views/account/index.php';
    }

    public function editForm($id)
    {
        $account = $this->accountModel->findById($id);
        if (!$account) {
            echo "❌ Không tìm thấy tài khoản.";
            return;
        }
        require 'views/account/edit.php';
    }

    public function create($data)
    {
        $this->accountModel->createSignUp($data);
        header("Location: " . BASE_URL . "index.php?route=/accounts");
    }

    public function edit($id, $data)
    {
        // Nếu có mật khẩu mới → mã hóa
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']); // Không cập nhật nếu không nhập
        }

        $this->accountModel->update($id, $data);
        header("Location: " . BASE_URL . "index.php?route=/accounts");
    }


    public function toggle($id)
    {
        $this->accountModel->toggleActivated($id);
        header("Location: " . BASE_URL . "index.php?route=/accounts");
    }

   public function resetPassword($id, $newPassword)
{
    $this->accountModel->resetPassword($id, $newPassword);
    header("Location: " . BASE_URL . "index.php?route=/accounts");
}


    public function assignRole($id, $role)
    {
        $this->accountModel->update($id, ['role' => $role]);
        header("Location: " . BASE_URL . "index.php?route=/accounts");
    }
}
