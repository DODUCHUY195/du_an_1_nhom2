<?php require_once APP_PATH . "/views/layouts/admin/header.php"; ?>
<?php require_once APP_PATH . "/views/layouts/admin/sidebar.php"; ?>

<main class="relative h-full max-h-screen transition-all xl:ml-68 rounded-xl">
    <?php require_once APP_PATH . "/views/layouts/admin/navbar.php"; ?>
    
    <div class="w-full p-6 mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Phân công hướng dẫn viên</h2>
            <a href="<?= BASE_URL . '?route=/schedules/detail&schedule_id=' . $schedule_id ?>" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Quay lại
            </a>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">Phân công hướng dẫn viên cho lịch #<?= $schedule_id ?></h3>
            
            <form method="POST" action="?route=/schedules/postAssignGuide" class="space-y-6">
                <input type="hidden" name="schedule_id" value="<?= $schedule_id ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Chọn hướng dẫn viên</label>
                        <select name="guide_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">-- Chọn hướng dẫn viên --</option>
                            <?php foreach($guides as $g): ?>
                                <option value="<?= $g['guide_id'] ?>" 
                                    <?= ($assigned && $assigned['guide_id'] == $g['guide_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($g['guide_name']) ?> 
                                    (ID: <?= $g['guide_id'] ?>)
                                    <?php if (!empty($g['license_no'])): ?>
                                        - <?= $g['license_no'] ?>
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Phân công
                    </button>
                </div>
            </form>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-lg font-semibold mb-4">Danh sách hướng dẫn viên</h3>
            
            <?php if (empty($guides)): ?>
                <p class="text-gray-500">Không có hướng dẫn viên nào.</p>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số bằng lái</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach($guides as $g): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"><?= $g['guide_id'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($g['guide_name']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?= $g['license_no'] ?? 'N/A' ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($assigned && $assigned['guide_id'] == $g['guide_id']): ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Đã phân công
                                            </span>
                                        <?php else: ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Chưa phân công
                                            </span>
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