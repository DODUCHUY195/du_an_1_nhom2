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
              <p class="text-sm text-slate-400">Theo dõi số lượng, xe và nguồn booking</p>
            </div>
            <a href="<?= BASE_URL . '?route=/bookings/create' ?>" class="px-4 py-2 text-sm font-semibold text-white rounded-lg bg-gradient-to-r from-blue-500 to-indigo-500">
              Bán tour - Đặt chỗ
            </a>
          </div>

          <div class="px-6 pt-4 space-y-4">
            <?php if (!empty($flashSuccess)): ?>
              <div class="px-4 py-3 text-sm text-green-800 bg-green-100 border border-green-200 rounded-lg">
                <?= htmlspecialchars($flashSuccess) ?>
              </div>
            <?php endif; ?>
            <?php if (!empty($flashError)): ?>
              <div class="px-4 py-3 text-sm text-red-800 bg-red-100 border border-red-200 rounded-lg">
                <?= htmlspecialchars($flashError) ?>
              </div>
            <?php endif; ?>

            <div class="grid gap-4 md:grid-cols-2">
              <div class="p-4 bg-slate-50 rounded-xl">
                <p class="text-xs font-semibold uppercase text-slate-500">Booking admin tạo</p>
                <p class="mt-2 text-2xl font-bold text-slate-800"><?= (int)($counts['admin'] ?? 0) ?></p>
              </div>
              <div class="p-4 bg-slate-50 rounded-xl">
                <p class="text-xs font-semibold uppercase text-slate-500">Booking khách tự tạo</p>
                <p class="mt-2 text-2xl font-bold text-slate-800"><?= (int)($counts['customer'] ?? 0) ?></p>
              </div>
            </div>

            <?php
            $statusOptions = [
              'pending'   => 'Pending',
              'confirmed' => 'Confirmed',
              'cancelled' => 'Cancelled',
            ];
            ?>
            <form method="GET" action="<?= BASE_URL ?>" class="grid gap-4 md:grid-cols-4">
              <input type="hidden" name="route" value="/bookings">

              <div>
                <label class="block mb-1 text-xs font-semibold text-slate-500">Tour</label>
                <select name="tour_id" class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring focus:ring-blue-200">
                  <option value="">Tất cả tour</option>
                  <?php foreach ($tours as $tour): ?>
                    <option value="<?= (int)$tour['tour_id'] ?>" <?= (string)($_GET['tour_id'] ?? '') === (string)$tour['tour_id'] ? 'selected' : '' ?>>
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
                    <?php $label = sprintf('%s - %s (còn %d ghế)',
                      htmlspecialchars($schedule['tour_name']),
                      date('d/m/Y', strtotime($schedule['depart_date'])),
                      (int)$schedule['available_seats']); ?>
                    <option value="<?= (int)$schedule['schedule_id'] ?>" <?= (string)($_GET['schedule_id'] ?? '') === (string)$schedule['schedule_id'] ? 'selected' : '' ?>>
                      <?= $label ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div>
                <label class="block mb-1 text-xs font-semibold text-slate-500">Trạng thái</label>
                <select name="status" class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring focus:ring-blue-200">
                  <option value="">Tất cả</option>
                  <?php foreach ($statusOptions as $value => $label): ?>
                    <option value="<?= $value ?>" <?= ($_GET['status'] ?? '') === $value ? 'selected' : '' ?>>
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
              <table class="items-center w-full mb-0 align-top border-collapse text-slate-600">
                <thead>
                  <tr>
                    <th class="px-6 py-3 text-xs font-bold text-left uppercase border-b text-slate-400">Mã Booking</th>
                    <th class="px-6 py-3 text-xs font-bold text-left uppercase border-b text-slate-400">Tour</th>
                    <th class="px-6 py-3 text-xs font-bold text-left uppercase border-b text-slate-400">Ngày đi - Ngày về</th>
                    <th class="px-6 py-3 text-xs font-bold text-left uppercase border-b text-slate-400">Khách hàng</th>
                    <th class="px-6 py-3 text-xs font-bold text-left uppercase border-b text-slate-400">Số chỗ đặt</th>
                    <th class="px-6 py-3 text-xs font-bold text-left uppercase border-b text-slate-400">Xe</th>
                    <th class="px-6 py-3 text-xs font-bold text-left uppercase border-b text-slate-400">Ghế trống xe</th>
                    <th class="px-6 py-3 text-xs font-bold text-left uppercase border-b text-slate-400">Xe còn trống</th>
                    <th class="px-6 py-3 text-xs font-bold text-left uppercase border-b text-slate-400">Trạng thái</th>
                    <th class="px-6 py-3 text-xs font-bold text-left uppercase border-b text-slate-400">Thao tác</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($bookings)): ?>
                    <tr>
                      <td colspan="10" class="px-6 py-4 text-center text-sm text-slate-400">Chưa có booking nào.</td>
                    </tr>
                  <?php else: ?>
                    <?php foreach ($bookings as $booking): ?>
                      <?php
                        $status = $booking['status'] ?? 'pending';
                        $badgeClass = [
                          'pending' => 'bg-amber-100 text-amber-700',
                          'confirmed' => 'bg-emerald-100 text-emerald-700',
                          'cancelled' => 'bg-rose-100 text-rose-700',
                        ][$status] ?? 'bg-slate-100 text-slate-700';
                        $depart = $booking['depart_date'] ? date('d/m/Y', strtotime($booking['depart_date'])) : '—';
                        $return = $booking['return_date'] ? date('d/m/Y', strtotime($booking['return_date'])) : '—';
                        $vehicleLabel = !empty($booking['vehicle_name'])
                            ? sprintf('%s (%s)', $booking['vehicle_name'], $booking['plate_number'])
                            : 'Chưa gán xe';
                        $vehicleSeats = isset($booking['vehicle_available_seats']) && $booking['vehicle_available_seats'] !== null
                            ? (int)$booking['vehicle_available_seats']
                            : '-';
                        $availableVehicles = isset($booking['available_vehicles']) ? (int)$booking['available_vehicles'] : 0;
                      ?>
                      <tr class="border-b last:border-0">
                        <td class="px-6 py-4">
                          <div class="font-semibold text-slate-700"><?= htmlspecialchars($booking['booking_code'] ?? ('BK' . str_pad($booking['booking_id'], 6, '0', STR_PAD_LEFT))) ?></div>
                          <div class="text-xs text-slate-400">#<?= (int)$booking['booking_id'] ?></div>
                        </td>
                        <td class="px-6 py-4">
                          <div class="font-semibold text-slate-800"><?= htmlspecialchars($booking['tour_name'] ?? '---') ?></div>
                          <div class="text-xs text-slate-400"><?= htmlspecialchars($booking['tour_code'] ?? '') ?></div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                          <?= $depart ?> - <?= $return ?>
                        </td>
                        <td class="px-6 py-4">
                          <div class="font-semibold text-slate-800"><?= htmlspecialchars($booking['customer_name'] ?? 'Khách lẻ') ?></div>
                          <div class="text-xs text-slate-400">Email: <?= htmlspecialchars($booking['email'] ?? '—') ?></div>
                          <div class="text-xs text-slate-400">SĐT: <?= htmlspecialchars($booking['phone'] ?? '—') ?></div>
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-800">
                          <?= (int)($booking['passenger_count'] ?? 0) ?> hành khách
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">
                          <?= htmlspecialchars($vehicleLabel) ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">
                          <?= $vehicleSeats ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">
                          <?= $availableVehicles ?> xe
                        </td>
                        <td class="px-6 py-4">
                          <span class="px-2 py-1 text-xs font-semibold rounded-full <?= $badgeClass ?>">
                            <?= htmlspecialchars($status) ?>
                          </span>
                        </td>
                        <td class="px-6 py-4 space-y-2 text-sm">
                          <a href="<?= BASE_URL . '?route=/customerBooking&booking_id=' . (int)$booking['booking_id'] ?>" class="inline-block px-3 py-1 text-xs font-semibold text-slate-600 bg-slate-100 rounded-lg">Xem khách</a>
                          <form method="POST" action="<?= BASE_URL . '?route=/bookings/update' ?>" class="flex items-center gap-2">
                            <input type="hidden" name="booking_id" value="<?= (int)$booking['booking_id'] ?>">
                            <select name="status" class="px-2 py-1 text-xs border rounded-lg">
                              <?php foreach ($statusOptions as $value => $label): ?>
                                <option value="<?= $value ?>" <?= $status === $value ? 'selected' : '' ?>><?= $label ?></option>
                              <?php endforeach; ?>
                            </select>
                            <button type="submit" class="px-3 py-1 text-xs font-semibold text-white rounded-md bg-gradient-to-r from-blue-500 to-indigo-500">Lưu</button>
                          </form>
                          <a href="<?= BASE_URL . '?route=/bookings/cancel&booking_id=' . (int)$booking['booking_id'] ?>" class="inline-block px-3 py-1 text-xs font-semibold text-rose-600 border border-rose-200 rounded-lg" onclick="return confirm('Bạn chắc chắn muốn hủy booking này?');">Hủy</a>
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