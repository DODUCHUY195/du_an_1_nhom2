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
                        <h2 class="text-xl font-bold mb-4">🧩 Quản lý Tài khoản & Phân quyền</h2>

                        <a href="<?= BASE_URL ?>index.php?route=/accounts/create"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">➕ Tạo tài khoản mới</a>
                    </div>
                    <div class="px-6 pt-4 text-center">


                        <table class="ble-auto w-full border-collapse border border-gray-300">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2">ID</th>
                                    <th class="px-4 py-2">Tên</th>
                                    <th class="px-4 py-2">Email</th>
                                    <th class="px-4 py-2">Vai trò</th>
                                    <th class="px-4 py-2">Trạng thái</th>
                                    <th class="px-4 py-2">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($accounts as $acc): ?>
                                    <tr class="border-t">
                                        <td class="px-4 py-2"><?= $acc['account_id'] ?></td>
                                        <td class="px-4 py-2"><?= $acc['full_name'] ?></td>
                                        <td class="px-4 py-2"><?= $acc['email'] ?></td>
                                        <td class="px-4 py-2"><?= $acc['role'] ?></td>
                                        <td class="px-4 py-2">
                                            <?= $acc['activated'] ? '✅ Mở' : '❌ Khóa' ?>
                                        </td>
                                        <td class="px-4 py-2 space-x-2">
                                            <a href="<?= BASE_URL ?>index.php?route=/accounts/edit&id=<?= $acc['account_id'] ?>"
                                                class="bg-yellow-500 hover:bg-yellow-600 text-black px-3 py-1 rounded">✏️ Sửa</a>
                                            <a href="<?= BASE_URL ?>index.php?route=/accounts/toggle&id=<?= $acc['account_id'] ?>"
                                                class="bg-gray-500 hover:bg-gray-600 text-black px-3 py-1 rounded">🔒 Khóa/Mở</a>
                                            <a href="<?= BASE_URL ?>index.php?route=/accounts/reset&id=<?= $acc['account_id'] ?>"
                                                class="bg-red-500 hover:bg-red-600 text-black px-3 py-1 rounded">🔑 Reset</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>


                        <div class="mt-4">
                            <a href="<?= BASE_URL ?>?route=/customerBooking/exportExcel&booking_id=<?= $booking_id ?>"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">📊 Xuất Excel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>


<?php require_once './views/layouts/admin/footer.php'; ?>