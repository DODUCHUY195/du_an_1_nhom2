<?php require_once './views/layouts/admin/header.php'; ?>
<?php require_once './views/layouts/admin/sidebar.php'; ?>

<main class="relative h-full max-h-screen transition-all xl:ml-68 rounded-xl">
    <?php require_once './views/layouts/admin/navbar.php'; ?>

    <div class="relative px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3">
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border p-6">
                    <h2 class="mb-6 text-xl font-semibold text-slate-700">Tạo booking cho khách</h2>

                    <?php if (empty($schedules)): ?>
                        <div class="p-4 mb-4 text-sm text-amber-800 bg-amber-50 border border-amber-200 rounded-lg">
                            Chưa có lịch tour nào mở. Vui lòng thêm lịch trước khi tạo booking.
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($error)): ?>
                        <div class="p-4 mb-4 text-sm text-red-800 bg-red-50 border border-red-200 rounded-lg">
                            <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?= BASE_URL . '?route=/bookings/create' ?>" class="grid gap-6 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label class="block mb-2 text-xs font-semibold text-slate-500">Tour & lịch khởi hành *</label>
                            <select name="schedule_id" id="scheduleSelect" class="w-full px-4 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200" required>
                                <option value="">-- Chọn tour & lịch --</option>
                                <?php foreach ($schedules as $schedule): ?>
                                    <?php $label = sprintf('%s - %s (còn %d ghế)',
                                        htmlspecialchars($schedule['tour_name']),
                                        date('d/m/Y', strtotime($schedule['depart_date'])),
                                        (int)$schedule['available_seats']); ?>
                                    <option value="<?= (int)$schedule['schedule_id'] ?>" <?= (string)($oldInput['schedule_id'] ?? '') === (string)$schedule['schedule_id'] ? 'selected' : '' ?>>
                                        <?= $label ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <p class="mt-1 text-xs text-slate-400">Danh sách chỉ hiển thị các lịch đang mở (status = open).</p>
                        </div>

                        <div>
                            <label class="block mb-2 text-xs font-semibold text-slate-500">Họ tên khách *</label>
                            <input type="text" name="customer_name" value="<?= htmlspecialchars($oldInput['full_name'] ?? $oldInput['customer_name'] ?? '') ?>" class="w-full px-4 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200" required>
                        </div>

                        <div>
                            <label class="block mb-2 text-xs font-semibold text-slate-500">Email *</label>
                            <input type="email" name="customer_email" value="<?= htmlspecialchars($oldInput['email'] ?? $oldInput['customer_email'] ?? '') ?>" class="w-full px-4 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200" required>
                        </div>

                        <div>
                            <label class="block mb-2 text-xs font-semibold text-slate-500">Số điện thoại *</label>
                            <input type="text" name="customer_phone" value="<?= htmlspecialchars($oldInput['phone'] ?? $oldInput['customer_phone'] ?? '') ?>" class="w-full px-4 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200" required>
                        </div>

                        <div>
                            <label class="block mb-2 text-xs font-semibold text-slate-500">Số lượng khách *</label>
                            <input type="number" name="passenger_count" min="1" value="<?= htmlspecialchars($oldInput['passenger_count'] ?? 1) ?>" class="w-full px-4 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200" required>
                        </div>

                        <div>
                            <label class="block mb-2 text-xs font-semibold text-slate-500">Chọn xe</label>
                            <select name="vehicle_id" id="vehicleSelect" class="w-full px-4 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                                <option value="">-- Chưa gán xe --</option>
                            </select>
                            <p class="mt-1 text-xs text-slate-400">Chỉ chọn khi cần khóa chỗ trên một xe cụ thể.</p>
                        </div>

                        <div>
                            <label class="block mb-2 text-xs font-semibold text-slate-500">Tiền cọc (VNĐ)</label>
                            <input type="number" name="deposit" min="0" step="50000" value="<?= htmlspecialchars($oldInput['deposit'] ?? 0) ?>" class="w-full px-4 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                        </div>

                        <div>
                            <label class="block mb-2 text-xs font-semibold text-slate-500">Phương thức thanh toán *</label>
                            <select name="payment_method" class="w-full px-4 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200" required>
                                <?php
                                $paymentOptions = [
                                    'cash' => 'Tiền mặt',
                                    'bank_transfer' => 'Chuyển khoản',
                                    'credit_card' => 'Thẻ tín dụng/POS',
                                    'ewallet' => 'Ví điện tử',
                                ];
                                foreach ($paymentOptions as $value => $label):
                                ?>
                                    <option value="<?= $value ?>" <?= ($oldInput['payment_method'] ?? '') === $value ? 'selected' : '' ?>>
                                        <?= $label ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label class="block mb-2 text-xs font-semibold text-slate-500">Trạng thái booking *</label>
                            <select name="status" class="w-full px-4 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200" required>
                                <?php
                                $statusOptions = [
                                    'pending' => 'Pending',
                                    'confirmed' => 'Confirmed',
                                    'cancelled' => 'Cancelled',
                                ];
                                foreach ($statusOptions as $value => $label):
                                ?>
                                    <option value="<?= $value ?>" <?= ($oldInput['status'] ?? 'pending') === $value ? 'selected' : '' ?>>
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
                </div>
            </div>
        </div>
    </div>
</main>

<?php
$vehiclesJson = json_encode($vehiclesBySchedule ?? [], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
$selectedVehicleJson = json_encode((string)($oldInput['vehicle_id'] ?? ''), JSON_UNESCAPED_UNICODE);
?>

<script>
    (function() {
        const vehiclesBySchedule = <?= $vehiclesJson ?: '{}' ?>;
        const presetVehicle = <?= $selectedVehicleJson ?>;
        const vehicleSelect = document.getElementById('vehicleSelect');
        const scheduleSelect = document.getElementById('scheduleSelect');

    function populateVehicles(scheduleId) {
      while (vehicleSelect.options.length > 1) {
        vehicleSelect.remove(1);
      }
      if (!scheduleId || !vehiclesBySchedule[scheduleId]) {
        return;
      }
      vehiclesBySchedule[scheduleId].forEach(vehicle => {
        const option = document.createElement('option');
        const available = vehicle.available_seats ?? (vehicle.total_seats - vehicle.booked_seats);
        option.value = vehicle.vehicle_id;
        option.textContent = `${vehicle.vehicle_name} (${vehicle.plate_number} - còn ${available} ghế)`;
                if (presetVehicle === String(vehicle.vehicle_id)) {
          option.selected = true;
        }
        vehicleSelect.appendChild(option);
      });
    }

    scheduleSelect?.addEventListener('change', (e) => {
      populateVehicles(e.target.value);
    });

        if (scheduleSelect && scheduleSelect.value) {
            populateVehicles(scheduleSelect.value);
        }
  })();
</script>

<?php require_once './views/layouts/admin/footer.php'; ?>