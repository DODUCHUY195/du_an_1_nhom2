<?php require_once APP_PATH . "/views/layouts/admin/header.php"; ?>
<?php require_once APP_PATH . "/views/layouts/admin/sidebar.php"; ?>

<main class="relative h-full max-h-screen transition-all xl:ml-68 rounded-xl">
    <?php require_once APP_PATH . "/views/layouts/admin/navbar.php"; ?>

    <div class="w-full p-6 mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Chi tiết Tour</h2>
            <div class="flex space-x-2">
                <a href="<?= BASE_URL . '?route=/tours/editForm&tour_id=' . $tour['tour_id'] ?>"
                    class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    Sửa Tour
                </a>
                <a href="<?= BASE_URL . '?route=/tours' ?>"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Quay lại
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Tour Information -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <h3 class="text-xl font-bold mb-4">Thông tin tour</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="font-semibold">Mã Tour:</label>
                            <p><?= $tour['tour_code'] ?></p>
                        </div>
                        <div>
                            <label class="font-semibold">Tên Tour:</label>
                            <p><?= htmlspecialchars($tour['tour_name']) ?></p>
                        </div>

                        <div>
                            <label class="font-semibold">Giá:</label>
                            <p><?= number_format($tour['price'], 0, ',', '.') ?> VNĐ</p>
                        </div>
                        <div>
                            <label class="font-semibold">Số ngày:</label>
                            <p><?= isset($tour['duration_days']) && $tour['duration_days'] > 0 ? $tour['duration_days'] . ' ngày' : 'Chưa xác định' ?></p>
                        </div>
                        <div>
                            <label class="font-semibold">Ngày tạo:</label>
                            <p><?= date('d/m/Y H:i', strtotime($tour['created_at'])) ?></p>
                        </div>
                        <div>
                            <label class="font-semibold">Trạng thái:</label>
                            <?php
                            $tourModel = new Tour();
                            $statusBadgeClass = $tourModel->getStatusBadgeClass($tour['status']);
                            $statusLabel = $tourModel->getStatusOptions()[$tour['status']] ?? 'Không xác định';
                            ?>
                            <div class="mt-1">
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-medium <?= $statusBadgeClass ?>">
                                    <?= $statusLabel ?>
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">
                                <?= $tourModel->getStatusDescription($tour['status']) ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-xl font-bold mb-4">Mô tả</h3>
                    <div class="prose max-w-none">
                        <?= nl2br(htmlspecialchars($tour['description'])) ?>
                    </div>
                </div>
            </div>

            <!-- Sidebar Information -->
            <div>
                <!-- Image -->
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <h3 class="text-lg font-bold mb-4">Hình ảnh</h3>
                    <?php if (!empty($tour['image'])): ?>
                        <img src="<?= BASE_URL ?>./<?= $tour['image'] ?>"
                            alt="Ảnh HDV" class="w-20 h-20 object-cover rounded-full">
                    <?php else: ?>
                        <span class="text-gray-400 italic">Chưa có ảnh</span>
                    <?php endif; ?>
                </div>

                <!-- Related Information -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold mb-4">Thông tin liên quan</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="font-semibold">Lịch khởi hành:</label>
                            <p><?= $scheduleCount ?> lịch</p>
                        </div>
                        <div>
                            <label class="font-semibold">Đặt chỗ:</label>
                            <p><?= $bookingCount ?> đơn</p>
                        </div>
                        <div>
                            <label class="font-semibold">Doanh thu:</label>
                            <p><?= number_format($totalRevenue, 0, ',', '.') ?> VNĐ</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    function updateStatus(newStatus) {
        if (confirm('Bạn có chắc chắn muốn thay đổi trạng thái tour?')) {
            // In a real implementation, this would make an AJAX call to update the status
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= BASE_URL ?>?route=/tours/updateStatus';

            const tourIdInput = document.createElement('input');
            tourIdInput.type = 'hidden';
            tourIdInput.name = 'tour_id';
            tourIdInput.value = '<?= $tour['tour_id'] ?>';
            form.appendChild(tourIdInput);

            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = newStatus;
            form.appendChild(statusInput);

            document.body.appendChild(form);
            form.submit();
        }
    }
</script>

<?php require_once APP_PATH . "/views/layouts/admin/footer.php"; ?>