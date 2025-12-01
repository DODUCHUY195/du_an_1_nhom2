<h2 class="text-xl font-bold mb-4">🔑 Reset mật khẩu</h2>

<form method="POST" action="<?= BASE_URL ?>index.php?route=/accounts/reset&id=<?= $_GET['id'] ?>" class="space-y-4 max-w-md bg-white p-6 rounded shadow">
    <label class="block text-sm font-medium text-gray-700">Mật khẩu mới</label>
    <input type="password" name="new_password" class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200" required>

    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Đặt lại mật khẩu</button>
    <a href="<?= BASE_URL ?>index.php?route=/accounts" class="ml-4 text-blue-500 hover:underline">↩️ Quay lại</a>
</form>
