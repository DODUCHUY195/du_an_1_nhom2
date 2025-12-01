<?php require_once './views/layouts/admin/header.php'; ?>
<?php require_once './views/layouts/admin/sidebar.php'; ?>
<?php require_once './views/layouts/admin/navbar.php'; ?>

<main class="relative h-full max-h-screen transition-all xl:ml-68 rounded-xl">
  <div class="px-6 pt-6 mx-auto">
    <div class="flex flex-wrap -mx-3">
      <div class="w-full max-w-full px-3 mb-6">
        <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl rounded-2xl">
          <div class="p-6 pb-0 mb-0 bg-blue rounded-t-2xl flex justify-between items-center">
            <h6 class="text-lg font-bold">Phân công Hướng dẫn viên cho Tour</h6>
          </div>

          <div class="px-6 pt-4">
            <form method="POST" class="space-y-4">
              <div>
                <label class="block text-sm font-medium">Chọn Tour</label>
                <select name="schedule_id" class="border rounded px-3 py-2 w-full" required>
                  <option value="">-- Chọn tour --</option>
                  <?php foreach ($schedules as $s): ?>
                    <option value="<?= $s['schedule_id'] ?>">
                      Tour #<?= $s['schedule_id'] ?> - <?= $s['depart_date'] ?> (<?= $s['meeting_point'] ?>)
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-black rounded">
                Phân công
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
