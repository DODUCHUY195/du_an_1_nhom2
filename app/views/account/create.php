<h2 class="text-xl font-bold mb-4">➕ Tạo tài khoản mới</h2>
<form method="POST" action="<?= BASE_URL ?>index.php?route=/accounts/create" class="space-y-4">
    <input name="full_name" placeholder="Họ và tên" class="border p-2 w-full" required>
    <input name="email" type="email" placeholder="Email" class="border p-2 w-full" required>
    <input name="phone" placeholder="Số điện thoại" class="border p-2 w-full">
    <input name="password" type="password" placeholder="Mật khẩu" class="border p-2 w-full" required>

    <select name="activated" class="border p-2 w-full">
        <option value="1">Mở</option>
        <option value="0">Khoá</option>
    </select>

    <select name="role" class="border p-2 w-full">
        <option value="AdminTong">Super Admin</option>
        <option value="QuanLy">Admin phụ</option>
        <option value="Customer">Khách hàng</option>
    </select>


    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Tạo mới</button>
</form>