<?php require_once APP_PATH . "/views/layouts/admin/header.php"; ?>
<?php require_once APP_PATH . "/views/layouts/admin/sidebar.php"; ?>

<main class="relative h-full max-h-screen transition-all xl:ml-68 rounded-xl">
    <?php require_once APP_PATH . "/views/layouts/admin/navbar.php"; ?>
    
    <div class="w-full p-6 mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Điều hành tour</h2>
        </div>
        
        <!-- Running Tours Section -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">Các tour đang chạy</h3>
            
            <?php if (empty($runningSchedules)): ?>
                <p class="text-gray-500">Không có tour nào đang chạy.</p>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STT</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tour</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày khởi hành</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hướng dẫn viên</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach($runningSchedules as $k => $schedule): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"><?= $k+1 ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium text-gray-900"><?= $schedule['tour_name'] ?></div>
                                        <div class="text-sm text-gray-500"><?= $schedule['tour_code'] ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?= date('d/m/Y', strtotime($schedule['depart_date'])) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($schedule['assigned_guide_id']): ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                <?= htmlspecialchars($schedule['guide_name']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Chưa phân công
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            <?= $schedule['status'] == 'open' ? 'Đang chạy' : ucfirst($schedule['status']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="<?= BASE_URL.'?route=/schedules/detail&schedule_id='.$schedule['schedule_id'] ?>" 
                                           class="text-indigo-600 hover:text-indigo-900 mr-3">Chi tiết</a>
                                        <?php if (!$schedule['assigned_guide_id']): ?>
                                            <a href="<?= BASE_URL.'?route=/schedules/assignGuideForm&schedule_id='.$schedule['schedule_id'] ?>" 
                                               class="text-green-600 hover:text-green-900">Phân công HDV</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">Phân công hướng dẫn viên</h3>
                <p class="text-gray-600 mb-4">Phân công hướng dẫn viên cho các tour đang chạy.</p>
                <a href="<?= BASE_URL.'?route=/schedules' ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Xem danh sách
                </a>
            </div>
            
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">Theo dõi tiến độ</h3>
                <p class="text-gray-600 mb-4">Xem tiến độ của các tour đang chạy.</p>
                <a href="<?= BASE_URL.'?route=/schedules' ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Xem tiến độ
                </a>
            </div>
            
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">Nhật ký hàng ngày</h3>
                <p class="text-gray-600 mb-4">Xem và quản lý nhật ký tour.</p>
                <a href="<?= BASE_URL.'?route=/schedules' ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Xem nhật ký
                </a>
            </div>
        </div>
    </div>
</main>

<?php require_once APP_PATH . "/views/layouts/admin/footer.php"; ?>