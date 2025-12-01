<h2>Đăng nhập</h2>
<form method="POST" action="<?= BASE_URL . 'index.php?route=/postLogin' ?>">
    <input name="email" placeholder="Email" required>
    <input name="password" type="password" placeholder="Mật khẩu" required>
    <button type="submit">Đăng nhập</button>
</form>
<p>Bạn chưa có tài khoản? <a href="<?= BASE_URL . 'index.php?route=/register' ?>">Đăng ký ngay</a></p>
