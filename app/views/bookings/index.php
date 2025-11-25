<?php require_once './views/layouts/admin/header.php'; ?>
<?php require_once './views/layouts/admin/sidebar.php'; ?>

<main class="relative h-full max-h-screen transition-all xl:ml-68 rounded-xl">
  <?php require_once './views/layouts/admin/navbar.php'; ?>

  <div class="w-full px-6 py-6 mx-auto">
    <div class="flex flex-wrap -mx-3">
      <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
          <div class="flex items-center justify-between p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
            <div>
              <h6 class="text-lg font-semibold text-slate-700">Quản lý booking</h6>
              <p class="text-sm text-slate-400">Theo dõi và cập nhật trạng thái đơn đặt tour</p>
            </div>
            <a href="<?= BASE_URL . '?route=/bookings/create' ?>" class="px-4 py-2 text-sm font-semibold text-white rounded-lg bg-gradient-to-r from-blue-500 to-indigo-500">
              Bán tour - Đặt chỗ
            </a>
          </div>

          <div class="px-6 pt-4">
            <?php if (!empty($flashSuccess)): ?>
              <div class="px-4 py-3 mb-4 text-sm text-green-800 bg-green-100 border border-green-200 rounded-lg">
                <?= htmlspecialchars($flashSuccess) ?>
              </div>
            <?php endif; ?>
            <?php if (!empty($flashError)): ?>
              <div class="px-4 py-3 mb-4 text-sm text-red-800 bg-red-100 border border-red-200 rounded-lg">
                <?= htmlspecialchars($flashError) ?>
              </div>
            <?php endif; ?>

            <form method="GET" action="<?= BASE_URL . '?route=/admin/bookings' ?>" class="grid gap-4 mb-6 md:grid-cols-4">
              <div>
                <label class="block mb-1 text-xs font-semibold text-slate-500">Tour</label>
                <select name="tour_id" class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring focus:ring-blue-200">
                  <option value="">Tất cả tour</option>
                  <?php foreach ($tours as $tour): ?>
                    <option value="<?= (int)$tour['tour_id'] ?>" <?= isset($filters['tour_id']) && (int)$filters['tour_id'] === (int)$tour['tour_id'] ? 'selected' : '' ?>>
                      <?= htmlspecialchars($tour['tour_name']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div>
                <label class="block mb-1 text-xs font-semibold text-slate-500">Lịch khởi hành</label>
                <select name="schedule_id" class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring focus:ring-blue-200">
                  <option value="">Tất cả lịch</option>
                  <?php foreach ($schedules as $schedule): ?>
                    <?php
                      $selected = isset($filters['schedule_id']) && (int)$filters['schedule_id'] === (int)$schedule['schedule_id'];
                      $departLabel = $schedule['depart_date'] ? date('d/m/Y', strtotime($schedule['depart_date'])) : 'Chưa xác định';
                    ?>
                    <option value="<?= (int)$schedule['schedule_id'] ?>" <?= $selected ? 'selected' : '' ?>>
                      <?= htmlspecialchars($schedule['tour_name']) . ' - ' . htmlspecialchars($departLabel) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div>
                <label class="block mb-1 text-xs font-semibold text-slate-500">Trạng thái</label>
                <select name="status" class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring focus:ring-blue-200">
                  <option value="">Tất cả</option>
                  <?php
                    $statusOptions = [
                      'pending'   => 'Pending',
                      'confirmed' => 'Confirmed',
                      'cancelled' => 'Cancelled',
                      'refunded'  => 'Refunded',
                    ];
                  ?>
                  <?php foreach ($statusOptions as $value => $label): ?>
                    <option value="<?= $value ?>" <?= isset($filters['status']) && $filters['status'] === $value ? 'selected' : '' ?>>
                      <?= $label ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 text-sm font-semibold text-white rounded-lg bg-gradient-to-r from-slate-600 to-slate-800">
                  Lọc
                </button>
              </div>
            </form>
          </div>

          <div class="flex-auto px-0 pt-0 pb-2">
            <div class="p-0 overflow-x-auto">
              <table class="items-center w-full mb-0 align-top border-collapse text-slate-500">
                <thead class="align-bottom">
                  <tr>
                    <th class="px-6 py-3 text-xs font-bold text-left uppercase align-middle bg-transparent border-b text-slate-400">ID</th>
                    <th class="px-6 py-3 text-xs font-bold text-left uppercase align-middle bg-transparent border-b text-slate-400">Code</th>
                    <th class="px-6 py-3 text-xs font-bold text-left uppercase align-middle bg-transparent border-b text-slate-400">Tour</th>
                    <th class="px-6 py-3 text-xs font-bold text-left uppercase align-middle bg-transparent border-b text-slate-400">Khởi hành</th>
                    <th class="px-6 py-3 text-xs font-bold text-left uppercase align-middle bg-transparent border-b text-slate-400">Khách hàng</th>
                    <th class="px-6 py-3 text-xs font-bold text-left uppercase align-middle bg-transparent border-b text-slate-400">Tổng tiền</th>
                    <th class="px-6 py-3 text-xs font-bold text-left uppercase align-middle bg-transparent border-b text-slate-400">Cọc</th>
                    <th class="px-6 py-3 text-xs font-bold text-left uppercase align-middle bg-transparent border-b text-slate-400">Trạng thái</th>
                    <th class="px-6 py-3 text-xs font-bold text-left uppercase align-middle bg-transparent border-b text-slate-400">Thao tác</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($bookings)): ?>
                    <tr>
                      <td colspan="9" class="px-6 py-4 text-center text-sm text-slate-400">Chưa có booking nào.</td>
                    </tr>
                  <?php else: ?>
                    <?php foreach ($bookings as $booking): ?>
                      <?php
                        $departLabel = $booking['depart_date'] ? date('d/m/Y', strtotime($booking['depart_date'])) : 'N/A';
                        $customerName = $booking['full_name'] ?? 'Khách lẻ';
                        $status = $booking['status'] ?? 'pending';
                      ?>
                      <tr>
                        <td class="px-6 py-4 text-sm font-semibold text-slate-600">#<?= htmlspecialchars($booking['booking_id']) ?></td>
                        <td class="px-6 py-4 text-sm"><?= htmlspecialchars($booking['booking_code']) ?></td>
                        <td class="px-6 py-4 text-sm">
                          <div class="font-semibold text-slate-700"><?= htmlspecialchars($booking['tour_name'] ?? '---') ?></div>
                          <div class="text-xs text-slate-400"><?= htmlspecialchars($booking['tour_code'] ?? '') ?></div>
                        </td>
                        <td class="px-6 py-4 text-sm"><?= htmlspecialchars($departLabel) ?></td>
                        <td class="px-6 py-4 text-sm">
                          <div class="font-semibold text-slate-700"><?= htmlspecialchars($customerName) ?></div>
                          <div class="text-xs text-slate-400"><?= htmlspecialchars($booking['email'] ?? '') ?></div>
                          <div class="text-xs text-slate-400"><?= htmlspecialchars($booking['phone'] ?? '') ?></div>
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-emerald-600"><?= number_format((float)$booking['total_amount'], 2) ?></td>
                        <td class="px-6 py-4 text-sm text-slate-600"><?= number_format((float)$booking['deposit'], 2) ?></td>
                        <td class="px-6 py-4 text-sm">
                          <span class="px-2 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-600">
                            <?= htmlspecialchars($status) ?>
                          </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                          <form method="POST" action="<?= BASE_URL . '?route=/admin/bookings/update' ?>" class="flex items-center gap-2">
                            <input type="hidden" name="booking_id" value="<?= (int)$booking['booking_id'] ?>">
                            <select name="status" class="px-2 py-1 text-xs border rounded-lg">
                              <?php foreach ($statusOptions as $value => $label): ?>
                                <option value="<?= $value ?>" <?= $status === $value ? 'selected' : '' ?>><?= $label ?></option>
                              <?php endforeach; ?>
                            </select>
                            <button type="submit" class="px-3 py-1 text-xs font-semibold text-white rounded-md bg-gradient-to-r from-blue-500 to-indigo-500">Cập nhật</button>
                          </form>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<?php require_once './views/layouts/admin/footer.php'; ?>
