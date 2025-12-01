<?php require_once "./views/layouts/admin/header.php"; ?>
<?php require_once "./views/layouts/admin/sidebar.php"; ?>

<main class="relative h-full max-h-screen transition-all xl:ml-68 rounded-xl">
  <?php require_once "./views/layouts/admin/navbar.php"; ?>

  <div class="w-full px-6 py-6 mx-auto">
    <h2 class="text-xl font-bold mb-4">Chi tiết HDV => <?= htmlspecialchars($guide['guide_id']) ?></h2>

    <!-- Thông tin HDV -->
    <div class="mb-6 space-y-2 bg-white shadow rounded-lg p-4">
      <p><strong>Họ tên:</strong> <?= htmlspecialchars($user['full_name'] ?? '') ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($user['email'] ?? '') ?></p>
      <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone'] ?? '') ?></p>
      <p><strong>License no.:</strong> <?= htmlspecialchars($guide['license_no'] ?? '') ?></p>
      <p><strong>Note:</strong> <?= htmlspecialchars($guide['note'] ?? '') ?></p>
    </div>

    <!-- Phân công lịch trình -->
    <div class="mb-8 bg-white shadow rounded-lg p-4">
      <h3 class="text-lg font-semibold mb-2">Phân công lịch trình</h3>
      <form method="post" action="<?= BASE_URL . '?route=/guides/assign' ?>" class="space-y-4">
        <input type="hidden" name="guide_id" value="<?= htmlspecialchars($guide['guide_id']) ?>">
        <label for="schedule_id" class="block text-sm font-medium text-gray-700">Chọn lịch trình:</label>
        <select name="schedule_id" id="schedule_id" 
                class="w-full border rounded px-3 py-2 text-sm focus:ring focus:ring-indigo-200">
          <?php foreach ($schedules as $s): ?>
            <option value="<?= $s['schedule_id'] ?>">
              <?= htmlspecialchars($s['depart_date'] ?? '') ?> - <?= htmlspecialchars($s['tour_name'] ?? '') ?>
            </option>
          <?php endforeach; ?>
        </select>
        <button type="submit" 
                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-black rounded text-sm transition">
          Xác nhận
        </button>
      </form>
    </div>

    <!-- Danh sách phân công -->
    <div class="mb-8 bg-white shadow rounded-lg p-4">
      <h3 class="text-lg font-semibold mb-2">Danh sách phân công</h3>
      <?php if (!empty($assignments)): ?>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-700 border rounded-lg">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
              <tr>
                <th class="px-4 py-2 text-center">ID</th>
                <th class="px-4 py-2 text-center">Tên chuyến hành trình</th>
                <th class="px-4 py-2 text-center">Ngày khởi hành</th>
                <th class="px-4 py-2 text-center">Hành động</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <?php foreach ($assignments as $a): ?>
                <tr class="hover:bg-gray-50">
                  <td class="px-4 py-2 text-center"><?= htmlspecialchars($a['assignment_id'] ?? '') ?></td>
                  <td class="px-4 py-2 text-center"><?= htmlspecialchars($a['tour_name'] ?? '') ?></td>
                  <td class="px-4 py-2 text-center"><?= htmlspecialchars($a['depart_date'] ?? '') ?></td>
                  <td class="px-4 py-2 text-center">
                    <form method="post" action="<?= BASE_URL . '?route=/guides/unassign' ?>" 
                          onsubmit="return confirm('Bạn có chắc muốn hủy phân công này?')">
                      <input type="hidden" name="assignment_id" value="<?= $a['assignment_id'] ?>">
                      <input type="hidden" name="guide_id" value="<?= $guide['guide_id'] ?>">
                      <button type="submit" 
                              class="px-3 py-1 bg-red-600 hover:bg-red-700 text-black rounded text-xs transition">
                        Hủy
                      </button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <p class="text-gray-500">Chưa có phân công.</p>
      <?php endif; ?>
    </div>

    <!-- Hiệu suất -->
    <div class="bg-white shadow rounded-lg p-4">
      <h3 class="text-lg font-semibold mb-2">Hiệu suất</h3>
      <p class="text-sm">Số tour đã dẫn: <b><?= htmlspecialchars($performance['total_tours'] ?? 0) ?></b></p>
    </div>
  </div>
</main>

<?php require_once "./views/layouts/admin/footer.php"; ?>
