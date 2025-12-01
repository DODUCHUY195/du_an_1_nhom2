<?php require_once './views/layouts/admin/header.php'; ?>
<?php require_once './views/layouts/admin/sidebar.php'; ?>
<?php require_once './views/layouts/admin/navbar.php'; ?>
<?php
// View: Form thêm khách vào booking
?>
<main class="relative h-full max-h-screen transition-all xl:ml-68 rounded-xl">
  <div class="px-6 pt-6 mx-auto">
    <div class="flex flex-wrap -mx-3">
      <div class="w-full max-w-full px-3 mb-6">
        <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl rounded-2xl">
          <!-- Header -->
          <div class="p-6 pb-0 bg-blue rounded-t-2xl flex justify-between items-center">
            <h6 class="text-lg font-bold">➕ Thêm khách vào Booking #<?= htmlspecialchars($booking_id) ?></h6>
          </div>

          <!-- Form -->
          <div class="px-6 pt-4">
            <form method="POST" action="<?= BASE_URL ?>?route=/customerBooking/postCreate&booking_id=<?= $booking_id ?>" class="space-y-4">
              
              <!-- Họ tên -->
              <div>
                <label class="block text-sm font-medium">Họ tên</label>
                <input type="text" name="full_name" class="border rounded px-3 py-2 w-full" required>
              </div>

              <!-- Giới tính -->
              <div>
                <label class="block text-sm font-medium">Giới tính</label>
                <select name="gender" class="border rounded px-3 py-2 w-full">
                  <option value="Nam">Nam</option>
                  <option value="Nữ">Nữ</option>
                  <option value="Khác">Khác</option>
                </select>
              </div>

              <!-- Năm sinh -->
              <div>
                <label class="block text-sm font-medium">Năm sinh</label>
                <input type="number" name="birth_year" class="border rounded px-3 py-2 w-full" min="1900" max="2025">
              </div>

              <!-- Số giấy tờ -->
              <div>
                <label class="block text-sm font-medium">Số giấy tờ (CMND/CCCD/Hộ chiếu)</label>
                <input type="text" name="id_number" class="border rounded px-3 py-2 w-full">
              </div>

              <!-- Yêu cầu đặc biệt -->
              <div>
                <label class="block text-sm font-medium">Yêu cầu đặc biệt</label>
                <textarea name="special_request" class="border rounded px-3 py-2 w-full"></textarea>
              </div>

              <!-- Buttons -->
              <div class="flex space-x-2">
                <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-black rounded">
                  ➕ Thêm khách
                </button>
                <a href="<?= BASE_URL ?>?route=/customerBooking&booking_id=<?= $booking_id ?>" 
                   class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-black rounded">Hủy</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>


<?php require_once './views/layouts/admin/footer.php'; ?>
