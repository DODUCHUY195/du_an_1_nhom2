<?php require_once './views/layouts/admin/header.php'; ?>
<?php require_once './views/layouts/admin/sidebar.php'; ?>
<?php require_once './views/layouts/admin/navbar.php'; ?>

<main class="relative h-full max-h-screen transition-all xl:ml-68 rounded-xl">
  <div class="px-6 pt-6 mx-auto">
    <div class="flex flex-wrap -mx-3">
      <div class="w-full max-w-full px-3 mb-6">
        <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl rounded-2xl">
          <div class="p-6 pb-0 mb-0 bg-blue rounded-t-2xl flex justify-between items-center">
            <h6 class="text-lg font-bold">Tạo hồ sơ Hướng dẫn viên</h6>
          </div>

          <div class="px-6 pt-4">
            <form method="POST" enctype="multipart/form-data" class="space-y-4">
              <div>
                <label class="block text-sm font-medium">Họ tên</label>
                <input type="text" name="full_name" class="border rounded px-3 py-2 w-full" required>
              </div>

              <div>
                <label class="block text-sm font-medium">Ngày sinh</label>
                <input type="date" name="birth_date" class="border rounded px-3 py-2 w-full">
              </div>

              <div>
                <label class="block text-sm font-medium">Liên hệ</label>
                <input type="text" name="contact" class="border rounded px-3 py-2 w-full">
              </div>

              <div>
                <label class="block text-sm font-medium">Chứng chỉ</label>
                <input type="text" name="license_no" class="border rounded px-3 py-2 w-full">
              </div>

              <div>
                <label class="block text-sm font-medium">Ảnh</label>
                <input type="file" name="photo" class="border rounded px-3 py-2 w-full">
              </div>

              <div>
                <label class="block text-sm font-medium">Ghi chú</label>
                <textarea name="note" class="border rounded px-3 py-2 w-full"></textarea>
              </div>

              <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-black rounded">
                Lưu hồ sơ
              </button>
              <a href="<?= BASE_URL ?>?route=/guides" class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-black rounded">
                Hủy
              </a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<?php require_once './views/layouts/admin/footer.php'; ?>
