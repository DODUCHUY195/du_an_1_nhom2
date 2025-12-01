<?php require_once './views/layouts/admin/header.php'; ?>
<?php require_once './views/layouts/admin/sidebar.php'; ?>
<?php require_once './views/layouts/admin/navbar.php'; ?>
<?php
// View: Danh sách khách trong booking
?>
<main class="relative h-full max-h-screen transition-all xl:ml-68 rounded-xl">
    <div class="px-6 pt-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mb-6">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl rounded-2xl">
                    <div class="p-6 pb-0 bg-blue rounded-t-2xl flex justify-between items-center">

                    </div>
                    <div class="px-6 pt-4">


                        <h2 class="text-xl font-bold mb-4">✏️ Sửa thông tin tài khoản</h2>

                        <form method="POST" action="<?= BASE_URL ?>index.php?route=/accounts/edit&id=<?= $account['account_id'] ?>" class="space-y-4 max-w-lg bg-white p-6 rounded shadow">
                            <!-- Họ và tên -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Họ và tên</label>
                                <input type="text" name="full_name" value="<?= htmlspecialchars($account['full_name']) ?>"
                                    class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200" required>
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" value="<?= htmlspecialchars($account['email']) ?>"
                                    class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200" required>
                            </div>

                            <!-- Số điện thoại -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Số điện thoại</label>
                                <input type="text" name="phone" value="<?= htmlspecialchars($account['phone']) ?>"
                                    class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200">
                            </div>

                            <!-- Mật khẩu mới -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Mật khẩu mới (nếu muốn đổi)</label>
                                <input type="password" name="password"
                                    class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200">
                            </div>

                            <!-- Vai trò -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Vai trò</label>
                                <select name="role" class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200">
                                    <option value="AdminTong" <?= $account['role'] === 'AdminTong' ? 'selected' : '' ?>>Super Admin</option>
                                    <option value="QuanLy" <?= $account['role'] === 'QuanLy' ? 'selected' : '' ?>>Admin phụ</option>
                                    <option value="Customer" <?= $account['role'] === 'Customer' ? 'selected' : '' ?>>Khách hàng</option>
                                </select>
                            </div>

                            <!-- Trạng thái -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Trạng thái</label>
                                <select name="activated" class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200">
                                    <option value="1" <?= $account['activated'] ? 'selected' : '' ?>>✅ Mở</option>
                                    <option value="0" <?= !$account['activated'] ? 'selected' : '' ?>>❌ Khóa</option>
                                </select>
                            </div>

                            <!-- Nút lưu -->
                            <div class="flex space-x-4">
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-black px-4 py-2 rounded">💾 Lưu thay đổi</button>
                                <a href="<?= BASE_URL ?>index.php?route=/accounts" class="bg-gray-500 hover:bg-gray-600 text-black px-4 py-2 rounded">↩️ Quay lại</a>
                            </div>
                        </form>




                    </div>
                </div>
            </div>
        </div>
    </div>
</main>


<?php require_once './views/layouts/admin/footer.php'; ?>