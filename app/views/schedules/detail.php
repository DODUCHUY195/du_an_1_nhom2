<?php require_once APP_PATH . "/views/layouts/admin/header.php"; ?>
<?php require_once APP_PATH . "/views/layouts/admin/sidebar.php"; ?>

<main class="relative h-full max-h-screen transition-all xl:ml-68 rounded-xl">
    <?php require_once APP_PATH . "/views/layouts/admin/navbar.php"; ?>
    
    <div class="w-full p-6 mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Chi tiết lịch khởi hành</h2>
            <a href="<?= BASE_URL . '?route=/schedules' ?>" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Quay lại
            </a>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Thông tin tour</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="font-medium">Mã tour:</label>
                            <p><?= $schedule['tour_code'] ?></p>
                        </div>
                        <div>
                            <label class="font-medium">Tên tour:</label>
                            <p><?= $schedule['tour_name'] ?></p>
                        </div>
                        <div>
                            <label class="font-medium">Ngày khởi hành:</label>
                            <p><?= date('d/m/Y', strtotime($schedule['depart_date'])) ?></p>
                        </div>
                        <div>
                            <label class="font-medium">Điểm gặp:</label>
                            <p><?= $schedule['meeting_point'] ?></p>
                        </div>
                        <div>
                            <label class="font-medium">Số ghế:</label>
                            <p><?= $schedule['seats_booked'] ?>/<?= $schedule['seats_total'] ?></p>
                        </div>
                        <div>
                            <label class="font-medium">Trạng thái:</label>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?= $schedule['status'] == 'open' ? 'bg-green-100 text-green-800' : 
                                   ($schedule['status'] == 'cancelled' ? 'bg-red-100 text-red-800' : 
                                   ($schedule['status'] == 'completed' ? 'bg-blue-100 text-blue-800' : 
                                   'bg-yellow-100 text-yellow-800')) ?>">
                                <?= $schedule['status'] == 'open' ? 'Mở' : 
                                   ($schedule['status'] == 'cancelled' ? 'Đã hủy' : 
                                   ($schedule['status'] == 'completed' ? 'Đã hoàn thành' : 
                                   'Chờ xử lý')) ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Hướng dẫn viên</h3>
                    <?php if ($assignedGuide): ?>
                        <div class="border rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16" />
                                <div class="ml-4">
                                    <p class="font-medium"><?= htmlspecialchars($assignedGuide['guide_name']) ?></p>
                                    <p class="text-sm text-gray-600">ID: <?= $assignedGuide['guide_id'] ?></p>
                                    <p class="text-sm text-gray-600">Số bằng lái: <?= $assignedGuide['license_no'] ?? 'N/A' ?></p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="<?= BASE_URL.'?route=/schedules/assignGuideForm&schedule_id='.$schedule['schedule_id'] ?>" 
                                   class="text-indigo-600 hover:text-indigo-900 text-sm">Thay đổi HDV</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="border border-dashed border-gray-300 rounded-lg p-6 text-center">
                            <p class="text-gray-500 mb-4">Chưa phân công hướng dẫn viên</p>
                            <a href="<?= BASE_URL.'?route=/schedules/assignGuideForm&schedule_id='.$schedule['schedule_id'] ?>" 
                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Phân công HDV
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold">Nhật ký tour</h3>
                <a href="<?= BASE_URL.'?route=/schedules/dailyLog&schedule_id='.$schedule['schedule_id'] ?>" 
                   class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Quản lý nhật ký
                </a>
            </div>
            
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
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
            
            <div class="mt-6 pt-6 border-t border-gray-200">
                <?php if ($logsApproved): ?>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800">Tour đã được xác nhận hoàn thành</h3>
                                <div class="mt-2 text-sm text-green-700">
                                    <p>Nhật ký tour đã được duyệt và tour được đánh dấu là đã hoàn thành.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="flex items-center justify-between">
                        <p class="text-gray-600">Xác nhận khi tour đã kết thúc để đánh dấu hoàn thành.</p>
                        <a href="<?= BASE_URL.'?route=/schedules/confirmFinish&schedule_id='.$schedule['schedule_id'] ?>" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                           onclick="return confirm('Bạn có chắc chắn muốn xác nhận kết thúc tour này?')">
                            Xác nhận kết thúc
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php require_once APP_PATH . "/views/layouts/admin/footer.php"; ?>