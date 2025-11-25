<?php require_once APP_PATH . "/views/layouts/admin/header.php"; ?>
<?php require_once APP_PATH . "/views/layouts/admin/sidebar.php"; ?>

<main class="relative h-full max-h-screen transition-all xl:ml-68 rounded-xl">
    <?php require_once APP_PATH . "/views/layouts/admin/navbar.php"; ?>
    
    <div class="w-full p-6 mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Thêm Lịch Trình</h2>
            <a href="<?= BASE_URL . '?route=/schedules' ?>" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Quay lại
            </a>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-6">
            <form action="<?= BASE_URL.'?route=/schedules/postAdd' ?>" method="POST">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 font-semibold">Tour</label>
                        <div class="relative">
                            <!-- Hidden input to store the selected tour ID -->
                            <input type="hidden" name="tour_id" id="tour_id" required>
                            
                            <!-- Searchable input -->
                            <input 
                                type="text" 
                                id="tour_search" 
                                placeholder="Tìm kiếm và chọn tour..." 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                autocomplete="off"
                            >
                            
                            <!-- Dropdown for search results -->
                            <div id="tour_dropdown" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg hidden max-h-60 overflow-y-auto">
                                <?php foreach($tours as $t): ?>
                                    <div 
                                        class="px-4 py-2 hover:bg-gray-100 cursor-pointer tour-option" 
                                        data-id="<?= $t['tour_id'] ?>"
                                        data-code="<?= htmlspecialchars($t['tour_code']) ?>"
                                        data-name="<?= htmlspecialchars($t['tour_name']) ?>"
                                    >
                                        <?= $t['tour_code'] ?> - <?= $t['tour_name'] ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block mb-2 font-semibold">Ngày khởi hành</label>
                        <input type="date" name="depart_date" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block mb-2 font-semibold">Điểm gặp</label>
                        <input type="text" name="meeting_point" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block mb-2 font-semibold">Số ghế tổng</label>
                        <input type="number" name="seats_total" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block mb-2 font-semibold">Trạng thái</label>
                        <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="open">Open</option>
                        </select>
                    </div>
                </div>
                
                <div class="text-center">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Thêm
                    </button>
                </div>
            </form>
        </div>
        
    </div>
</main>

<?php require_once APP_PATH . "/views/layouts/admin/footer.php"; ?>

<!-- Add JavaScript for searchable dropdown -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tourSearch = document.getElementById('tour_search');
    const tourIdInput = document.getElementById('tour_id');
    const tourDropdown = document.getElementById('tour_dropdown');
    const tourOptions = document.querySelectorAll('.tour-option');
    
    // Show dropdown when input is focused
    tourSearch.addEventListener('focus', function() {
        tourDropdown.classList.remove('hidden');
    });
    
    // Hide dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!tourSearch.contains(e.target) && !tourDropdown.contains(e.target)) {
            tourDropdown.classList.add('hidden');
        }
    });
    
    // Filter options based on search input
    tourSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        tourOptions.forEach(option => {
            const code = option.dataset.code.toLowerCase();
            const name = option.dataset.name.toLowerCase();
            
            if (code.includes(searchTerm) || name.includes(searchTerm)) {
                option.classList.remove('hidden');
            } else {
                option.classList.add('hidden');
            }
        });
        
        // Show dropdown when searching
        tourDropdown.classList.remove('hidden');
    });
    
    // Handle option selection
    tourOptions.forEach(option => {
        option.addEventListener('click', function() {
            const id = this.dataset.id;
            const code = this.dataset.code;
            const name = this.dataset.name;
            
            // Set the hidden input value
            tourIdInput.value = id;
            
            // Set the display text
            tourSearch.value = code + ' - ' + name;
            
            // Hide dropdown
            tourDropdown.classList.add('hidden');
        });
    });
});
</script>