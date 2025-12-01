<h2>Đăng ký</h2>
<form method="POST" action="<?= BASE_URL . 'index.php?route=/postRegister' ?>">
    <input name="full_name" placeholder="Họ và tên" required>
    <input name="email" type="email" placeholder="Email" required>
    <input name="phone" placeholder="Số điện thoại">
    <input name="password" type="password" placeholder="Mật khẩu" required>
    <select name="role">
        <option value="Customer">Khách hàng</option>
        <option value="QuanLy">Quản lý</option>
        <option value="AdminTong">Admin tổng</option>
    </select>
    <button type="submit">Đăng ký</button>
</form>
<p>Đã có tài khoản? <a href="<?= BASE_URL . 'index.php?route=/login' ?>">Đăng nhập ngay</a></p>
