<?php require_once APP_PATH . "/views/layouts/admin/header.php"; ?>
<?php require_once APP_PATH . "/views/layouts/admin/sidebar.php"; ?>

<main class="relative h-full max-h-screen transition-all xl:ml-68 rounded-xl">
    <?php require_once APP_PATH . "/views/layouts/admin/navbar.php"; ?>
    
    <div class="w-full p-6 mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Sửa nhật ký tour</h2>
            <a href="<?= BASE_URL . '?route=/schedules/dailyLog&schedule_id=' . $schedule_id ?>" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Quay lại
            </a>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-lg font-semibold mb-4">Chỉnh sửa nhật ký</h3>
            
            <form method="POST" action="?route=/schedules/updateDailyLog" class="space-y-4">
                <input type="hidden" name="log_id" value="<?= $logData['log_id'] ?>">
                <input type="hidden" name="schedule_id" value="<?= $schedule_id ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ngày tạo</label>
                        <input type="text" value="<?= date('d/m/Y H:i', strtotime($logData['created_at'])) ?>" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 bg-gray-100" readonly>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hướng dẫn viên</label>
                        <input type="text" value="<?= htmlspecialchars($logData['guide_id']) ?>" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 bg-gray-100" readonly>
                        <p class="mt-1 text-sm text-gray-500">Không thể thay đổi hướng dẫn viên sau khi tạo nhật ký</p>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nội dung</label>
                        <textarea name="content" rows="4" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                  placeholder="Nhập nội dung nhật ký..."><?= htmlspecialchars($logData['content']) ?></textarea>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sự cố (nếu có)</label>
                        <textarea name="incident" rows="4" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                  placeholder="Mô tả sự cố nếu có..."><?= htmlspecialchars($logData['incident'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cách xử lý</label>
                        <textarea name="resolution" rows="4" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                  placeholder="Cách xử lý sự cố..."><?= htmlspecialchars($logData['resolution'] ?? '') ?></textarea>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <a href="<?= BASE_URL . '?route=/schedules/dailyLog&schedule_id=' . $schedule_id ?>" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Hủy
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-900 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cập nhật nhật ký
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require_once APP_PATH . "/views/layouts/admin/footer.php"; ?>