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
              <h6 class="text-lg font-semibold text-slate-700">Bán tour - Đặt chỗ</h6>
              <p class="text-sm text-slate-400">Nhập thông tin khách và lịch tour để tạo booking</p>
            </div>
            <a href="<?= BASE_URL . '?route=/admin/bookings' ?>" class="px-4 py-2 text-sm font-semibold text-white rounded-lg bg-gradient-to-r from-slate-600 to-slate-800">
              Quản lý booking
            </a>
          </div>

          <div class="px-6 py-6">
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

            <?php if (!empty($error)): ?>
              <div class="px-4 py-3 mb-4 text-sm text-red-800 bg-red-100 border border-red-200 rounded-lg">
                <?= $error ?>
              </div>
            <?php endif; ?>

            <?php if (empty($schedules)): ?>
              <p class="text-sm text-slate-500">Chưa có lịch tour nào. Vui lòng tạo lịch trước khi bán tour.</p>
            <?php else: ?>
              <?php
                $paymentOptions = [
                  'cash' => 'Tiền mặt',
                  'bank_transfer' => 'Chuyển khoản',
                  'card' => 'Thẻ (POS)'
                ];
              ?>
              <form method="POST" action="<?= BASE_URL . '?route=/bookings/create' ?>" class="grid gap-5 md:grid-cols-2">
                <div class="md:col-span-2">
                  <label class="block mb-2 text-xs font-semibold text-slate-500">Họ tên khách *</label>
                  <input type="text" name="customer_name" value="<?= htmlspecialchars($oldInput['customer_name'] ?? '') ?>" class="w-full px-4 py-2 text-sm border rounded-lg focus:outline-none focus:ring focus:ring-blue-200" required>
                </div>

                <div>
                  <label class="block mb-2 text-xs font-semibold text-slate-500">Email *</label>
                  <input type="email" name="customer_email" value="<?= htmlspecialchars($oldInput['customer_email'] ?? '') ?>" class="w-full px-4 py-2 text-sm border rounded-lg focus:outline-none focus:ring focus:ring-blue-200" required>
                </div>

                <div>
                  <label class="block mb-2 text-xs font-semibold text-slate-500">Số điện thoại *</label>
                  <input type="text" name="customer_phone" value="<?= htmlspecialchars($oldInput['customer_phone'] ?? '') ?>" class="w-full px-4 py-2 text-sm border rounded-lg focus:outline-none focus:ring focus:ring-blue-200" required>
                </div>

                <div class="md:col-span-2">
                  <label class="block mb-2 text-xs font-semibold text-slate-500">Lịch tour *</label>
                  <select name="schedule_id" class="w-full px-4 py-2 text-sm border rounded-lg focus:outline-none focus:ring focus:ring-blue-200" required>
                    <option value="">-- Chọn lịch --</option>
                    <?php foreach ($schedules as $schedule): ?>
                      <?php
                        $available = isset($schedule['available_seats'])
                          ? (int)$schedule['available_seats']
                          : max(0, (int)$schedule['seats_total'] - (int)$schedule['seats_booked']);
                        $departLabel = $schedule['depart_date'] ? date('d/m/Y', strtotime($schedule['depart_date'])) : 'Chưa xác định';
                        $selected = (int)($oldInput['schedule_id'] ?? 0) === (int)$schedule['schedule_id'];
                      ?>
                      <option value="<?= (int)$schedule['schedule_id'] ?>" <?= $selected ? 'selected' : '' ?> <?= $available <= 0 ? 'disabled' : '' ?>>
                        <?= htmlspecialchars($schedule['tour_name']) ?> - <?= htmlspecialchars($departLabel) ?> (còn <?= $available ?> ghế)
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div>
                  <label class="block mb-2 text-xs font-semibold text-slate-500">Số lượng khách *</label>
                  <input type="number" name="passenger_count" min="1" value="<?= htmlspecialchars($oldInput['passenger_count'] ?? 1) ?>" class="w-full px-4 py-2 text-sm border rounded-lg focus:outline-none focus:ring focus:ring-blue-200" required>
                </div>

                <div>
                  <label class="block mb-2 text-xs font-semibold text-slate-500">Số tiền cọc</label>
                  <input type="number" step="0.01" min="0" name="deposit_amount" value="<?= htmlspecialchars($oldInput['deposit_amount'] ?? 0) ?>" class="w-full px-4 py-2 text-sm border rounded-lg focus:outline-none focus:ring focus:ring-blue-200">
                </div>

                <div>
                  <label class="block mb-2 text-xs font-semibold text-slate-500">Phương thức thanh toán *</label>
                  <select name="payment_method" class="w-full px-4 py-2 text-sm border rounded-lg focus:outline-none focus:ring focus:ring-blue-200" required>
                    <?php foreach ($paymentOptions as $value => $label): ?>
                      <option value="<?= $value ?>" <?= ($oldInput['payment_method'] ?? 'cash') === $value ? 'selected' : '' ?>>
                        <?= $label ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div class="md:col-span-2">
                  <button type="submit" class="w-full px-4 py-3 text-sm font-semibold text-white uppercase rounded-lg bg-gradient-to-r from-blue-500 to-indigo-500">
                    Tạo booking
                  </button>
                </div>
              </form>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<?php require_once './views/layouts/admin/footer.php'; ?>
