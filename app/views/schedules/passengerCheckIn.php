<?php require_once APP_PATH . "/views/layouts/admin/header.php"; ?>
<?php require_once APP_PATH . "/views/layouts/admin/sidebar.php"; ?>

<main class="relative h-full max-h-screen transition-all xl:ml-68 rounded-xl">
    <?php require_once APP_PATH . "/views/layouts/admin/navbar.php"; ?>
    
    <div class="w-full p-6 mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Điểm danh hành khách</h2>
            <a href="<?= BASE_URL . '?route=/schedules/detail&schedule_id=' . $schedule_id ?>" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Quay lại
            </a>
        </div>
        
        <!-- Schedule Info -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">Thông tin lịch trình</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="font-semibold">Tour:</label>
                    <p><?= htmlspecialchars($schedule['tour_name']) ?> (<?= $schedule['tour_code'] ?>)</p>
                </div>
                <div>
                    <label class="font-semibold">Ngày khởi hành:</label>
                    <p><?= date('d/m/Y', strtotime($schedule['depart_date'])) ?></p>
                </div>
                <div>
                    <label class="font-semibold">Điểm gặp:</label>
                    <p><?= htmlspecialchars($schedule['meeting_point']) ?></p>
                </div>
            </div>
        </div>
        
        <!-- Check-in Statistics -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">Thống kê điểm danh</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-blue-100 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-blue-800"><?= $checkInStats['total_passengers'] ?></div>
                    <div class="text-blue-600">Tổng số hành khách</div>
                </div>
                <div class="bg-green-100 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-green-800"><?= $checkInStats['checked_in_count'] ?></div>
                    <div class="text-green-600">Đã điểm danh</div>
                </div>
                <div class="bg-red-100 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-red-800"><?= $checkInStats['not_checked_in_count'] ?></div>
                    <div class="text-red-600">Chưa điểm danh</div>
                </div>
            </div>
        </div>
        
        <!-- Passenger List -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-lg font-semibold mb-4">Danh sách hành khách</h3>
            
            <?php if (empty($passengers)): ?>
                <div class="text-center py-8">
                    <p class="text-gray-500 mb-4">Chưa có hành khách nào.</p>
                    <p class="text-gray-600 mb-6">Hệ thống sẽ tự động tạo danh sách hành khách dựa trên các đặt tour đã có.</p>
                    <a href="?route=/schedules/createPassengers&schedule_id=<?= $schedule_id ?>" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-900 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Tạo danh sách hành khách
                    </a>
                    <p class="text-gray-500 mt-4 text-sm">Hoặc <a href="?route=/schedules/passengerCheckIn&schedule_id=<?= $schedule_id ?>" class="text-indigo-600 hover:text-indigo-800">tải lại trang</a> để hệ thống tự động tạo</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STT</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên hành khách</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Yêu cầu đặc biệt</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach($passengers as $index => $passenger): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?= $index + 1 ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?= htmlspecialchars($passenger['full_name']) ?>
                                        <?php if ($passenger['age']): ?>
                                            <span class="text-xs text-gray-500">(<?= $passenger['age'] ?> tuổi)</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?= htmlspecialchars($passenger['customer_name']) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if (!empty($passenger['special_request'])): ?>
                                            <button 
                                                onclick="showSpecialRequest(<?= $passenger['passenger_id'] ?>)" 
                                                class="text-blue-600 hover:text-blue-900 underline"
                                            >
                                                Xem yêu cầu
                                            </button>
                                            <div id="special-request-<?= $passenger['passenger_id'] ?>" class="hidden mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded">
                                                <?= htmlspecialchars($passenger['special_request']) ?>
                                            </div>
                                        <?php else: ?>
                                            <button 
                                                onclick="showUpdateForm(<?= $passenger['passenger_id'] ?>)" 
                                                class="text-blue-600 hover:text-blue-900 underline"
                                            >
                                                Thêm yêu cầu
                                            </button>
                                            <div id="update-form-<?= $passenger['passenger_id'] ?>" class="hidden mt-2">
                                                <form method="POST" action="?route=/schedules/updateSpecialRequest">
                                                    <input type="hidden" name="passenger_id" value="<?= $passenger['passenger_id'] ?>">
                                                    <input type="hidden" name="schedule_id" value="<?= $schedule_id ?>">
                                                    <textarea name="special_request" placeholder="Nhập yêu cầu đặc biệt..." class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" rows="2"></textarea>
                                                    <div class="mt-2">
                                                        <button type="submit" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                            Lưu
                                                        </button>
                                                        <button type="button" onclick="hideUpdateForm(<?= $passenger['passenger_id'] ?>)" class="ml-2 inline-flex items-center px-3 py-1 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                            Hủy
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($passenger['checked_in'] == 1): ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Đã điểm danh
                                            </span>
                                        <?php else: ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Chưa điểm danh
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <?php if ($passenger['checked_in'] == 1): ?>
                                            <a href="?route=/schedules/checkOutPassenger&passenger_id=<?= $passenger['passenger_id'] ?>&schedule_id=<?= $schedule_id ?>" 
                                               class="text-yellow-600 hover:text-yellow-900"
                                               onclick="return confirm('Bạn có chắc chắn muốn hủy điểm danh hành khách này?')">
                                                Hủy điểm danh
                                            </a>
                                        <?php else: ?>
                                            <a href="?route=/schedules/checkInPassenger&passenger_id=<?= $passenger['passenger_id'] ?>&schedule_id=<?= $schedule_id ?>" 
                                               class="text-green-600 hover:text-green-900"
                                               onclick="return confirm('Bạn có chắc chắn muốn điểm danh hành khách này?')">
                                                Điểm danh
                                            </a>
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

<script>
function showSpecialRequest(passengerId) {
    const requestDiv = document.getElementById('special-request-' + passengerId);
    if (requestDiv.classList.contains('hidden')) {
        requestDiv.classList.remove('hidden');
    } else {
        requestDiv.classList.add('hidden');
    }
}

function showUpdateForm(passengerId) {
    // Hide all other forms first
    document.querySelectorAll('[id^="update-form-"]').forEach(form => {
        form.classList.add('hidden');
    });
    
    // Show the specific form
    const formDiv = document.getElementById('update-form-' + passengerId);
    formDiv.classList.remove('hidden');
}

function hideUpdateForm(passengerId) {
    const formDiv = document.getElementById('update-form-' + passengerId);
    formDiv.classList.add('hidden');
}
</script>

<?php require_once APP_PATH . "/views/layouts/admin/footer.php"; ?>