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
                            <label class="font-semibold">Danh mục:</label>
                            <p><?= $tour['category_name'] ?? 'Chưa phân loại' ?></p>
                        </div>
                        <div>
                            <label class="font-semibold">Giá:</label>
                            <p><?= number_format($tour['price'], 0, ',', '.') ?> VNĐ</p>
                        </div>
                        <div>
                            <label class="font-semibold">Ngày bắt đầu:</label>
                            <p><?= $tour['start_date'] ? date('d/m/Y', strtotime($tour['start_date'])) : 'Chưa xác định' ?></p>
                        </div>
                        <div>
                            <label class="font-semibold">Ngày kết thúc:</label>
                            <p><?= $tour['end_date'] ? date('d/m/Y', strtotime($tour['end_date'])) : 'Chưa xác định' ?></p>
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
                            <span class="px-3 py-1 rounded-full text-sm font-medium <?= $statusBadgeClass ?>">
                                <?= $statusLabel ?>
                            </span>
                            <p class="text-sm text-gray-600 mt-1">
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
                        <img src="<?= BASE_URL ?>../<?= $tour['image'] ?>" alt="Tour Image" class="w-full h-48 object-cover rounded-lg">
                    <?php else: ?>
                        <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                            <span class="text-gray-500">Không có hình ảnh</span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Status Actions -->
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <h3 class="text-lg font-bold mb-4">Hành động trạng thái</h3>
                    <div class="space-y-3">
                        <?php 
                        $tourModel = new Tour();
                        $currentStatus = $tour['status'];
                        $statusOptions = $tourModel->getStatusOptions();
                        ?>
                        
                        <?php if ($currentStatus != 'active'): ?>
                        <button onclick="updateStatus('active')" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                            Kích hoạt tour
                        </button>
                        <?php endif; ?>
                        
                        <?php if ($currentStatus != 'inactive'): ?>
                        <button onclick="updateStatus('inactive')" class="w-full bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm">
                            Ngừng hoạt động
                        </button>
                        <?php endif; ?>
                        
                        <?php if ($currentStatus != 'suspended'): ?>
                        <button onclick="updateStatus('suspended')" class="w-full bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded text-sm">
                            Tạm ngưng
                        </button>
                        <?php endif; ?>
                        
                        <?php if ($currentStatus != 'cancelled'): ?>
                        <button onclick="updateStatus('cancelled')" class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm">
                            Hủy tour
                        </button>
                        <?php endif; ?>
                    </div>
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