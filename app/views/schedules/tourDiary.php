<?php require_once APP_PATH . "/views/layouts/admin/header.php"; ?>
<?php require_once APP_PATH . "/views/layouts/admin/sidebar.php"; ?>

<main class="relative h-full max-h-screen transition-all xl:ml-68 rounded-xl">
    <?php require_once APP_PATH . "/views/layouts/admin/navbar.php"; ?>
    
    <div class="w-full p-6 mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Nhật ký tour</h2>
            <a href="<?= BASE_URL . '?route=/schedules' ?>" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Quay lại
            </a>
        </div>
        
        <!-- Tour Diary Table -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-lg font-semibold mb-4">Tất cả nhật ký tour</h3>
            
            <?php if (empty($logs)): ?>
                <p class="text-gray-500">Chưa có nhật ký nào.</p>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tour</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">HDV</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nội dung nhật ký</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sự cố</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cách xử lý</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach($logs as $log): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?= date('d/m/Y H:i', strtotime($log['created_at'])) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900"><?= htmlspecialchars($log['tour_name']) ?></div>
                                        <div class="text-sm text-gray-500"><?= $log['tour_code'] ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?= htmlspecialchars($log['guide_name']) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?= htmlspecialchars($log['content']) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if (!empty($log['incident'])): ?>
                                            <?= htmlspecialchars($log['incident']) ?>
                                        <?php else: ?>
                                            <span class="text-gray-400">Không có</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if (!empty($log['resolution'])): ?>
                                            <?= htmlspecialchars($log['resolution']) ?>
                                        <?php else: ?>
                                            <span class="text-gray-400">Không có</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php require_once APP_PATH . "/views/layouts/admin/footer.php"; ?>