<?php require_once APP_PATH . "/views/layouts/admin/header.php"; ?>
<?php require_once APP_PATH . "/views/layouts/admin/sidebar.php"; ?>

<main class="relative h-full max-h-screen transition-all xl:ml-68 rounded-xl">
    <?php require_once APP_PATH . "/views/layouts/admin/navbar.php"; ?>
    
    <div class="w-full p-6 mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Nhật ký tour</h2>
            <a href="<?= BASE_URL . '?route=/schedules/detail&schedule_id=' . $schedule_id ?>" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Quay lại
            </a>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">Thêm nhật ký mới</h3>
            
            <form method="POST" action="?route=/schedules/addDailyLog" class="space-y-4">
                <input type="hidden" name="schedule_id" value="<?= $schedule_id ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hướng dẫn viên</label>
                        <select name="guide_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Chọn hướng dẫn viên</option>
                            <?php foreach($guides as $guide): ?>
                                <option value="<?= $guide['guide_id'] ?>" <?= (isset($assignedGuide) && $assignedGuide['guide_id'] == $guide['guide_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($guide['guide_name']) ?> (ID: <?= $guide['guide_id'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nội dung</label>
                        <textarea name="content" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                  placeholder="Nhập nội dung nhật ký..."></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sự cố (nếu có)</label>
                        <textarea name="incident" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                  placeholder="Mô tả sự cố nếu có..."></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cách xử lý</label>
                        <textarea name="resolution" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                  placeholder="Cách xử lý sự cố..."></textarea>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-900 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Thêm nhật ký
                    </button>
                </div>
            </form>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-lg font-semibold mb-4">Danh sách nhật ký</h3>
            
            <?php if (empty($logs)): ?>
                <p class="text-gray-500">Chưa có nhật ký nào.</p>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hướng dẫn viên</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nội dung</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach($logs as $log): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?= date('d/m/Y H:i', strtotime($log['created_at'])) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?= htmlspecialchars($log['guide_name']) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?= htmlspecialchars($log['content']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="?route=/schedules/editDailyLog&log_id=<?= $log['log_id'] ?>&schedule_id=<?= $schedule_id ?>" 
                                           class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            Sửa
                                        </a>
                                        <a href="?route=/schedules/deleteDailyLog&log_id=<?= $log['log_id'] ?>&schedule_id=<?= $schedule_id ?>" 
                                           class="text-red-600 hover:text-red-900"
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa nhật ký này?')">
                                            Xóa
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($groupedLogs)): ?>
        <div class="bg-white rounded-xl shadow-md p-6 mt-6">
            <h3 class="text-lg font-semibold mb-4">Nhật ký theo ngày</h3>
            
            <div class="space-y-4">
                <?php foreach($groupedLogs as $group): ?>
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 mb-2"><?= date('d/m/Y', strtotime($group['log_date'])) ?></h4>
                        <div class="text-sm text-gray-600">
                            <?= $group['logs'] ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once APP_PATH . "/views/layouts/admin/footer.php"; ?>