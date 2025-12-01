<?php require_once './views/layouts/admin/header.php'; ?>
<?php require_once './views/layouts/admin/sidebar.php'; ?>
<?php require_once './views/layouts/admin/navbar.php'; ?>
<?php
// View: Danh sách khách trong booking
?>
<main class="relative h-full max-h-screen transition-all xl:ml-68 rounded-xl">
  <div class="px-6 pt-6 mx-auto">
    <div class="flex flex-wrap -mx-3">
      <div class="w-full max-w-full px-3 mb-6">
        <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl rounded-2xl">
          <div class="p-6 pb-0 bg-blue rounded-t-2xl flex justify-between items-center">
            <h6 class="text-lg font-bold">Danh sách khách Booking #<?= htmlspecialchars($booking_id) ?></h6>
            <a href="<?= BASE_URL ?>?route=/customerBooking/create&booking_id=<?= $booking_id ?>"
               class="px-4 py-2 bg-green-600 hover:bg-green-700 text-black rounded">➕ Thêm khách</a>
          </div>
          <div class="px-6 pt-4">
            <table class="table-auto w-full border-collapse border border-gray-300">
              <thead class="bg-gray-100">
                <tr>
                  <th class="border px-4 py-2">STT</th>
                  <th class="border px-4 py-2">Họ tên</th>
                  <th class="border px-4 py-2">Giới tính</th>
                  <th class="border px-4 py-2">Năm sinh</th>
                  <th class="border px-4 py-2">Số giấy tờ</th>
                  <th class="border px-4 py-2">Yêu cầu đặc biệt</th>
                  <th class="border px-4 py-2">Điểm danh</th>
                  <th class="border px-4 py-2">Hành động</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($customers)): foreach ($customers as $i => $c): ?>
                  <tr class="hover:bg-gray-50">
                    <td class="border px-4 py-2 text-center"><?= $i+1 ?></td>
                    <td class="border px-4 py-2"><?= htmlspecialchars($c['full_name']) ?></td>
                    <td class="border px-4 py-2 text-center"><?= htmlspecialchars($c['gender']) ?></td>
                    <td class="border px-4 py-2 text-center"><?= htmlspecialchars($c['birth_year']) ?></td>
                    <td class="border px-4 py-2"><?= htmlspecialchars($c['id_number']) ?></td>
                    <td class="border px-4 py-2">
                      <form method="POST" action="<?= BASE_URL ?>?route=/customerBooking/updateRequest&customer_id=<?= $c['customer_id'] ?>">
                        <input type="hidden" name="booking_id" value="<?= $booking_id ?>">
                        <input type="text" name="special_request" value="<?= htmlspecialchars($c['special_request']) ?>" 
                               class="border px-2 py-1 w-full">
                        <button type="submit" class="mt-1 bg-blue-500 text-white px-2 py-1 rounded text-sm">Cập nhật</button>
                      </form>
                    </td>
                    <td class="border px-4 py-2 text-center">
                      <?php if ($c['checked_in']): ?>
                        <span class="text-green-600 font-bold">✅ Đã điểm danh</span>
                      <?php else: ?>
                        <a href="<?= BASE_URL ?>?route=/customerBooking/checkIn&customer_id=<?= $c['customer_id'] ?>&booking_id=<?= $booking_id ?>"
                           class="bg-green-500 hover:bg-green-600 text-black px-2 py-1 rounded text-sm">Điểm danh</a>
                      <?php endif; ?>
                    </td>
                    <td class="border px-4 py-2 text-center">
                      <a href="<?= BASE_URL ?>?route=/customerBooking/editCustomer&customer_id=<?= $c['customer_id'] ?>"
                         class="bg-yellow-500 hover:bg-yellow-600 text-black px-2 py-1 rounded text-sm">✏️ Sửa</a>
                    </td>
                  </tr>
                <?php endforeach; else: ?>
                  <tr>
                    <td colspan="8" class="px-4 py-6 text-center text-gray-500">Không có khách nào trong booking này.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>

            <div class="mt-4">
              <a href="<?= BASE_URL ?>?route=/customerBooking/exportExcel&booking_id=<?= $booking_id ?>" 
                 class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">📊 Xuất Excel</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>


<?php require_once './views/layouts/admin/footer.php'; ?>