<?php require_once './views/layouts/admin/header.php'; ?>
<?php require_once './views/layouts/admin/sidebar.php'; ?>
<?php require_once './views/layouts/admin/navbar.php'; ?>

<main class="relative w-full max-h-screen transition-all xl:ml-68 rounded-xl">
  <div class="px-6 pt-6 mx-auto">
    <div class="flex flex-wrap -mx-3">
      <div class="w-full max-w-full px-3 mb-6">
        <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl rounded-2xl">
          <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
            <h6 class="text-lg font-bold">Sửa khách hàng</h6>
          </div>

          <div class="px-6 py-6">

            <form method="POST" action="?route=/customerBooking/updateCustomer" class="space-y-4">
              <input type="hidden" name="customer_id" value="<?= htmlspecialchars($customer['customer_id'] ?? '') ?>">
              <input type="hidden" name="booking_id" value="<?= htmlspecialchars($customer['booking_id'] ?? '') ?>">

              <div>
                <label class="block font-medium">Họ tên</label>
                <input type="text" name="full_name" value="<?= htmlspecialchars($customer['full_name'] ?? '') ?>" required class="w-full border px-3 py-2 rounded">
              </div>

              <div>
                <label class="block font-medium">Giới tính</label>
                <select name="gender" required class="w-full border px-3 py-2 rounded">
                  <option value="Nam" <?= ($customer['gender'] ?? '') === 'Nam' ? 'selected' : '' ?>>Nam</option>
                  <option value="Nữ" <?= ($customer['gender'] ?? '') === 'Nữ' ? 'selected' : '' ?>>Nữ</option>
                  <option value="Khác" <?= ($customer['gender'] ?? '') === 'Khác' ? 'selected' : '' ?>>Khác</option>
                </select>
              </div>

              <div>
                <label class="block font-medium">Năm sinh</label>
                <input type="number" name="birth_year" value="<?= htmlspecialchars($customer['birth_year'] ?? '') ?>" min="1900" max="2025" required class="w-full border px-3 py-2 rounded">
              </div>

              <div>
                <label class="block font-medium">Số giấy tờ</label>
                <input type="text" name="id_number" value="<?= htmlspecialchars($customer['id_number'] ?? '') ?>" required class="w-full border px-3 py-2 rounded">
              </div>

              <div>
                <label class="block font-medium">Yêu cầu đặc biệt</label>
                <textarea name="special_request" class="w-full border px-3 py-2 rounded"><?= htmlspecialchars($customer['special_request'] ?? '') ?></textarea>
              </div>

              <button type="submit" class="bg-yellow-600 text-black px-4 py-2 rounded">Cập nhật</button>
              <a href="?route=/customerBooking&booking_id=<?= urlencode($customer['booking_id'] ?? '') ?>" class="ml-4 text-blue-600">← Quay lại danh sách</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<?php require_once './views/layouts/admin/footer.php'; ?>