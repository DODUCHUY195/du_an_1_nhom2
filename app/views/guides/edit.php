<?php require_once './views/layouts/admin/header.php'; ?>
<?php require_once './views/layouts/admin/sidebar.php'; ?>
<?php require_once './views/layouts/admin/navbar.php'; ?>

<main class="relative h-full max-h-screen transition-all xl:ml-68 rounded-xl">
    <div class="px-6 pt-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mb-6">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-blue rounded-t-2xl flex justify-between items-center">
                        <h6 class="text-lg font-bold">Sửa thông tin Hướng dẫn viên</h6>
                    </div>

                    <div class="px-6 pt-4">
                        <form method="POST" enctype="multipart/form-data" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium">Tên</label>
                                <input type="text" name="full_name" value="<?= htmlspecialchars($guide['full_name']) ?>" class="border rounded px-3 py-2 w-full">
                            </div>

                            <div>
                                <label class="block text-sm font-medium">Ngày sinh</label>
                                <input type="date" name="birth_date" value="<?= htmlspecialchars($guide['birth_date']) ?>" class="border rounded px-3 py-2 w-full">
                            </div>

                            <div>
                                <label class="block text-sm font-medium">Liên hệ</label>
                                <input type="text" name="contact" value="<?= htmlspecialchars($guide['contact']) ?>" class="border rounded px-3 py-2 w-full">
                            </div>

                            <div>
                                <label class="block text-sm font-medium">Chứng chỉ</label>
                                <input type="text" name="license_no" value="<?= htmlspecialchars($guide['license_no']) ?>" class="border rounded px-3 py-2 w-full">
                            </div>

                            <div>
                                <label class="block text-sm font-medium">Ảnh hiện tại</label>
                                <?php if (!empty($guide['photo'])): ?>
                                    <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($guide['photo']) ?>"
                                        alt="Ảnh HDV" class="w-20 h-20 object-cover rounded-full">
                                <?php else: ?>
                                    <span class="text-gray-400 italic">Chưa có ảnh</span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Chọn ảnh mới (nếu muốn thay)</label>
                                <input type="file" name="photo" class="border rounded px-3 py-2 w-full">
                            </div>

                            <div>
                                <label class="block text-sm font-medium">Ghi chú</label>
                                <textarea name="note" class="border rounded px-3 py-2 w-full"><?= htmlspecialchars($guide['note']) ?></textarea>
                            </div>

                            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-black rounded">
                                Cập nhật
                            </button>
                            <a href="<?= BASE_URL ?>?route=/guides" class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-black rounded">
                                Hủy
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once './views/layouts/admin/footer.php'; ?>