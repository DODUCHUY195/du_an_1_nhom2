<h2 class="text-xl font-bold mb-4">🛠️ Gán vai trò cho tài khoản</h2>

<form method="POST" action="<?= BASE_URL ?>index.php?route=/accounts/assignRole&id=<?= $_GET['id'] ?>" class="space-y-4 max-w-md bg-white p-6 rounded shadow">
    <label class="block text-sm font-medium text-gray-700">Chọn vai trò</label>
    <select name="role" class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200">
        <option value="SuperAdmin">Super Admin</option>
        <option value="AdminPhu">Admin phụ</option>
        <option value="HDV">Hướng dẫn viên</option>
        <option value="Customer">Khách hàng</option>
    </select>

    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">Gán vai trò</button>
    <a href="<?= BASE_URL ?>index.php?route=/accounts" class="ml-4 text-blue-500 hover:underline">↩️ Quay lại</a>
</form>
