<?php require_once APP_PATH . "/views/layouts/admin/header.php"; ?>
<?php require_once APP_PATH . "/views/layouts/admin/sidebar.php"; ?>

<main class="relative h-full max-h-screen transition-all xl:ml-68 rounded-xl">
    <?php require_once APP_PATH . "/views/layouts/admin/navbar.php"; ?>
    
    <div class="w-full p-6 mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Danh sách lịch trình</h2>
            <div class="flex space-x-2">
                <a href="<?= BASE_URL . '?route=/schedules/operationDashboard' ?>" 
                   class="bg-green-500 hover:bg-green-700 text-black font-bold py-2 px-4 rounded">
                    Điều hành tour
                </a>
                <a href="<?= BASE_URL . '?route=/schedules/addForm' ?>" 
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Thêm lịch trình
                </a>
            </div>
        </div>
        
        <!-- Advanced Search Form -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">Tìm kiếm nâng cao</h3>
            <form method="GET" action="<?= BASE_URL ?>">
                <input type="hidden" name="route" value="/schedules">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block mb-2 font-semibold">Tour</label>
                        <select name="tour_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Tất cả tour</option>
                            <?php foreach($tours as $tour): ?>
                                <option value="<?= $tour['tour_id'] ?>" <?= (isset($_GET['tour_id']) && $_GET['tour_id'] == $tour['tour_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($tour['tour_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block mb-2 font-semibold">Trạng thái</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Tất cả trạng thái</option>
                            <option value="pending" <?= (isset($_GET['status']) && $_GET['status'] == 'pending') ? 'selected' : '' ?>>Chờ xử lý</option>
                            <option value="open" <?= (isset($_GET['status']) && $_GET['status'] == 'open') ? 'selected' : '' ?>>Mở</option>
                            <option value="completed" <?= (isset($_GET['status']) && $_GET['status'] == 'completed') ? 'selected' : '' ?>>Đã hoàn thành</option>
                            <option value="cancelled" <?= (isset($_GET['status']) && $_GET['status'] == 'cancelled') ? 'selected' : '' ?>>Đã hủy</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block mb-2 font-semibold">Tìm kiếm</label>
                        <input type="text" name="search" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" placeholder="Tên tour hoặc điểm gặp" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Tìm kiếm
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-6">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STT</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ảnh</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tour</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày khởi hành</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày trở về</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Điểm gặp</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số ghế tổng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số ghế đã đặt</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">HDV</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach($list as $k => $s): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?= $k+1 ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if (!empty($s['image'])): ?>
                                    <img src="<?= BASE_URL ?>../<?= $s['image'] ?>" alt="Tour Image" class="w-16 h-16 object-cover rounded">
                                <?php else: ?>
                                    <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                        <span class="text-gray-500 text-xs">No Image</span>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900"><?= $s['tour_name'] ?></div>
                                <div class="text-sm text-gray-500"><?= $s['tour_code'] ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= date('d/m/Y', strtotime($s['depart_date'])) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= isset($s['return_date']) ? date('d/m/Y', strtotime($s['return_date'])) : 'Chưa xác định' ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= $s['meeting_point'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= $s['seats_total'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= $s['seats_booked'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if (!empty($s['guide_name'])): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <?= $s['guide_name'] ?>
                                    </span>
                                    <br>
                                    <a href="<?= BASE_URL.'?route=/schedules/removeGuide&id='.$s['assigned_guide_id'].'&schedule_id='.$s['schedule_id'] ?>" 
                                       class="text-red-600 hover:text-red-900 text-xs"
                                       onclick="return confirm('Bạn có chắc muốn hủy phân công HDV này?')">
                                        Hủy
                                    </a>
                                <?php else: ?>
                                    <!-- Button to open assign guide modal -->
                                    <button onclick="openAssignModal(<?= $s['schedule_id'] ?>)" 
                                            class="text-indigo-600 hover:text-indigo-900 text-xs">
                                        Phân công
                                    </button>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    <?= $s['status'] == 'open' ? 'bg-green-100 text-green-800' : 
                                       ($s['status'] == 'cancelled' ? 'bg-red-100 text-red-800' : 
                                       ($s['status'] == 'completed' ? 'bg-blue-100 text-blue-800' : 
                                       'bg-yellow-100 text-yellow-800')) ?>">
                                    <?= $s['status'] == 'open' ? 'Mở' : 
                                       ($s['status'] == 'cancelled' ? 'Đã hủy' : 
                                       ($s['status'] == 'completed' ? 'Đã hoàn thành' : 
                                       'Chờ xử lý')) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="<?= BASE_URL.'?route=/schedules/detail&schedule_id='.$s['schedule_id'] ?>" 
                                   class="text-indigo-600 hover:text-indigo-900 mr-3">Chi tiết</a>
                                <a href="<?= BASE_URL.'?route=/schedules/editForm&schedule_id='.$s['schedule_id'] ?>" 
                                   class="text-indigo-600 hover:text-indigo-900 mr-3">Sửa</a>
                                <a href="<?= BASE_URL.'?route=/schedules/delete&schedule_id='.$s['schedule_id'] ?>" 
                                   class="text-red-600 hover:text-red-900" 
                                   onclick="return confirm('Bạn có chắc muốn xoá/ẩn?')">Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6 mt-6">
                <div class="flex flex-1 justify-between sm:hidden">
                    <?php if ($page > 1): ?>
                    <a href="?route=/schedules&page=<?= $page - 1 ?>&tour_id=<?= isset($_GET['tour_id']) ? $_GET['tour_id'] : '' ?>&status=<?= isset($_GET['status']) ? $_GET['status'] : '' ?>&search=<?= isset($_GET['search']) ? urlencode($_GET['search']) : '' ?>" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Trước</a>
                    <?php endif; ?>
                    
                    <?php if ($page < $totalPages): ?>
                    <a href="?route=/schedules&page=<?= $page + 1 ?>&tour_id=<?= isset($_GET['tour_id']) ? $_GET['tour_id'] : '' ?>&status=<?= isset($_GET['status']) ? $_GET['status'] : '' ?>&search=<?= isset($_GET['search']) ? urlencode($_GET['search']) : '' ?>" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Sau</a>
                    <?php endif; ?>
                </div>
                <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Hiển thị <span class="font-medium"><?= ($page - 1) * 5 + 1 ?></span> đến <span class="font-medium"><?= min($page * 5, $totalSchedules) ?></span> trong tổng số <span class="font-medium"><?= $totalSchedules ?></span> kết quả
                        </p>
                    </div>
                    <div>
                        <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                            <?php if ($page > 1): ?>
                            <a href="?route=/schedules&page=<?= $page - 1 ?>&tour_id=<?= isset($_GET['tour_id']) ? $_GET['tour_id'] : '' ?>&status=<?= isset($_GET['status']) ? $_GET['status'] : '' ?>&search=<?= isset($_GET['search']) ? urlencode($_GET['search']) : '' ?>" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                <span class="sr-only">Trước</span>
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <?php endif; ?>
                            
                            <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                            <a href="?route=/schedules&page=<?= $i ?>&tour_id=<?= isset($_GET['tour_id']) ? $_GET['tour_id'] : '' ?>&status=<?= isset($_GET['status']) ? $_GET['status'] : '' ?>&search=<?= isset($_GET['search']) ? urlencode($_GET['search']) : '' ?>" class="<?= $i == $page ? 'relative z-10 inline-flex items-center bg-indigo-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600' : 'relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0' ?>"><?= $i ?></a>
                            <?php endfor; ?>
                            
                            <?php if ($page < $totalPages): ?>
                            <a href="?route=/schedules&page=<?= $page + 1 ?>&tour_id=<?= isset($_GET['tour_id']) ? $_GET['tour_id'] : '' ?>&status=<?= isset($_GET['status']) ? $_GET['status'] : '' ?>&search=<?= isset($_GET['search']) ? urlencode($_GET['search']) : '' ?>" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                <span class="sr-only">Sau</span>
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <?php endif; ?>
                        </nav>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Assign Guide Modal -->
    <div id="assignGuideModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-1/3">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-medium text-gray-900">Phân công hướng dẫn viên</h3>
            </div>
            <form id="assignGuideForm" method="GET" action="<?= BASE_URL ?>">
                <input type="hidden" name="route" value="/schedules/assignGuideFromList">
                <input type="hidden" name="schedule_id" id="schedule_id_input">
                <div class="px-6 py-4">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Chọn hướng dẫn viên</label>
                        <select name="guide_id" id="guideSelect" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Chọn hướng dẫn viên</option>
                            <?php foreach($guides as $guide): ?>
                                <option value="<?= $guide['guide_id'] ?>"><?= $guide['full_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="px-6 py-4 border-t bg-gray-50 flex justify-end space-x-3">
                    <button type="button" onclick="closeAssignModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Hủy
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Phân công
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    function openAssignModal(scheduleId) {
        document.getElementById('schedule_id_input').value = scheduleId;
        document.getElementById('assignGuideModal').classList.remove('hidden');
        document.getElementById('assignGuideModal').classList.add('flex');
    }
    
    function closeAssignModal() {
        document.getElementById('assignGuideModal').classList.add('hidden');
        document.getElementById('assignGuideModal').classList.remove('flex');
    }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('assignGuideModal');
        if (event.target === modal) {
            closeAssignModal();
        }
    }
</script>

<?php require_once APP_PATH . "/views/layouts/admin/footer.php"; ?>