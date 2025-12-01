<?php require_once './views/layouts/admin/header.php'; ?>
<?php require_once './views/layouts/admin/sidebar.php'; ?>
<?php require_once './views/layouts/admin/navbar.php'; ?>


<main class="relative h-full max-h-screen transition-all xl:ml-68 rounded-xl">
  <div class="px-6 pt-6 mx-auto">
    <div class="flex flex-wrap -mx-3">
      <div class="w-full max-w-full px-3 mb-6">
        <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl rounded-2xl">
          <div class="p-6 pb-0 bg-blue rounded-t-2xl flex justify-between items-center">
            <h6 class="text-lg font-bold">Danh sách khách Booking</h6>
            
          </div>
          <div class="px-6 pt-4">
            
  <table class="table-auto w-full border-collapse border border-gray-300">
    <thead class="bg-gray-100">
      <tr>
        <th class="border px-4 py-2">Mã Booking</th>
        <th class="border px-4 py-2">Ngày đặt</th>
        <th class="border px-4 py-2">Thao tác</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($bookings)): foreach ($bookings as $b): ?>
        <tr>
          <td class="border px-4 py-2"><?= htmlspecialchars($b['booking_code']) ?></td>
          <td class="border px-4 py-2"><?= htmlspecialchars($b['booking_date']) ?></td>
          <td class="border px-4 py-2">
            <a href="?route=/customerBooking&booking_id=<?= $b['booking_id'] ?>" class="bg-blue-500 text-white px-3 py-1 rounded">Xem khách</a>
          </td>
        </tr>
      <?php endforeach; else: ?>
        <tr>
          <td colspan="3" class="px-4 py-6 text-center text-gray-500">Không có booking nào.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>

          
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<?php require_once './views/layouts/admin/footer.php'; ?>
