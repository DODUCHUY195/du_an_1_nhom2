<?php require_once APP_PATH . "/views/layouts/admin/header.php"; ?>
<?php require_once APP_PATH . "/views/layouts/admin/sidebar.php"; ?>

<main class="relative h-full max-h-screen transition-all xl:ml-68 rounded-xl">
    <?php require_once APP_PATH . "/views/layouts/admin/navbar.php"; ?>
    
    <div class="w-full p-6 mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Sửa Lịch Trình</h2>
            <a href="<?= BASE_URL . '?route=/schedules' ?>" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Quay lại
            </a>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-6">
            <form action="<?= BASE_URL.'?route=/schedules/postEdit' ?>" method="POST">
                <input type="hidden" name="schedule_id" value="<?= $schedule['schedule_id'] ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 font-semibold">Tour</label>
                        <select name="tour_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <?php foreach($tours as $t): ?>
                                <option value="<?= $t['tour_id'] ?>" <?= $t['tour_id']==$schedule['tour_id']?'selected':'' ?>>
                                    <?= $t['tour_name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block mb-2 font-semibold">Ngày khởi hành</label>
                        <input type="date" name="depart_date" value="<?= $schedule['depart_date'] ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block mb-2 font-semibold">Ngày trở về</label>
                        <input type="date" name="return_date" value="<?= isset($schedule['return_date']) ? $schedule['return_date'] : '' ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block mb-2 font-semibold">Điểm gặp</label>
                        <input type="text" name="meeting_point" value="<?= $schedule['meeting_point'] ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block mb-2 font-semibold">Số ghế tổng</label>
                        <input type="number" name="seats_total" value="<?= $schedule['seats_total'] ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block mb-2 font-semibold">Số ghế đã đặt</label>
                        <input type="number" name="seats_booked" value="<?= $schedule['seats_booked'] ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block mb-2 font-semibold">Trạng thái</label>
                        <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="pending" <?= $schedule['status']=='pending'?'selected':'' ?>>Chờ xử lý</option>
                            <option value="completed" <?= $schedule['status']=='completed'?'selected':'' ?>>Đã hoàn thành</option>
                            <option value="cancelled" <?= $schedule['status']=='cancelled'?'selected':'' ?>>Đã hủy</option>
                            <option value="open" <?= $schedule['status']=='open'?'selected':'' ?>>Mở</option>
                        </select>
                    </div>
                    
                    <!-- Guide Assignment Field -->
                    <div>
                        <label class="block mb-2 font-semibold">Hướng dẫn viên</label>
                        <select name="guide_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Chọn hướng dẫn viên</option>
                            <?php foreach($guides as $guide): ?>
                                <option value="<?= $guide['guide_id'] ?>" <?= (isset($assignedGuide['guide_id']) && $assignedGuide['guide_id'] == $guide['guide_id']) ? 'selected' : '' ?>>
                                    <?= $guide['guide_name'] ?><?= !empty($guide['license_no']) ? ' (' . $guide['license_no'] . ')' : '' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (!empty($assignedGuide)): ?>
                            <div class="mt-2 text-sm text-gray-500">
                                Hiện tại: <?= $assignedGuide['guide_name'] ?><?= !empty($assignedGuide['license_no']) ? ' (' . $assignedGuide['license_no'] . ')' : '' ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="text-center">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Cập nhật
                    </button>
                </div>
            </form>
        </div>
        
    </div>
</main>

<?php require_once APP_PATH . "/views/layouts/admin/footer.php"; ?>