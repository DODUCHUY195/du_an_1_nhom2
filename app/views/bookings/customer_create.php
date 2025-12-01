<?php require './views/layouts/user/header.php'; ?>

<main class="main">
  <section class="section py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <h2 class="mb-4 text-center">Đặt tour</h2>

          <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
          <?php endif; ?>

          <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
          <?php endif; ?>

          <form method="POST" action="<?= BASE_URL ?>?route=/booking/customer/store" class="card shadow-sm p-4">
            <div class="mb-3">
              <label class="form-label">Chọn tour & lịch khởi hành *</label>
              <select name="schedule_id" class="form-select" required>
                <option value="">-- Chọn lịch --</option>
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
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Họ tên *</label>
                <input type="text" name="full_name" value="<?= htmlspecialchars($oldInput['full_name'] ?? '') ?>" class="form-control" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Số điện thoại *</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($oldInput['phone'] ?? '') ?>" class="form-control" required>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Email *</label>
                <input type="email" name="email" value="<?= htmlspecialchars($oldInput['email'] ?? '') ?>" class="form-control" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Số lượng khách *</label>
                <input type="number" name="passenger_count" min="1" value="<?= htmlspecialchars($oldInput['passenger_count'] ?? 1) ?>" class="form-control" required>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Phương thức thanh toán *</label>
              <select name="payment_method" class="form-select" required>
                <?php
                $paymentOptions = [
                  'ewallet' => 'Ví điện tử',
                  'credit_card' => 'Thẻ tín dụng/ghi nợ',
                  'bank_transfer' => 'Chuyển khoản',
                ];
                foreach ($paymentOptions as $value => $label):
                ?>
                  <option value="<?= $value ?>" <?= ($oldInput['payment_method'] ?? '') === $value ? 'selected' : '' ?>>
                    <?= $label ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Yêu cầu đặc biệt</label>
              <textarea name="special_request" rows="4" class="form-control" placeholder="Ví dụ: cần thêm bữa chay, ghế gần cửa sổ..."><?= htmlspecialchars($oldInput['special_request'] ?? '') ?></textarea>
            </div>

            <div class="text-center">
              <button type="submit" class="btn btn-primary px-4">Đặt tour ngay</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</main>

<?php require './views/layouts/user/footer.php'; ?>
