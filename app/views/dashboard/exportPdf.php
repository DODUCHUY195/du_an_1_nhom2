<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title ?? 'Báo cáo doanh thu') ?></title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; margin: 0; padding: 32px; color: #0f172a; }
        h1, h2, h3 { margin: 0 0 12px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; }
        .card { border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; background: #fff; }
        .card h3 { font-size: 24px; color: #0f172a; }
        .card p { font-size: 12px; text-transform: uppercase; color: #64748b; letter-spacing: 0.08em; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #e2e8f0; padding: 8px 10px; font-size: 13px; }
        th { background: #f8fafc; text-align: left; text-transform: uppercase; font-size: 11px; color: #475569; }
        td.text-right { text-align: right; }
        .muted { color: #94a3b8; font-size: 12px; }
        .text-center { text-align: center; }
        .header { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 24px; }
        .header span { font-size: 13px; color: #64748b; }
        @media print {
            body { padding: 0 16px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <h1>Báo cáo doanh thu du lịch</h1>
            <span>Cập nhật tới ngày <?= date('d/m/Y') ?></span>
        </div>
        <button class="no-print" onclick="window.print()" style="padding:8px 14px;border:none;border-radius:8px;background:#0f172a;color:#fff;cursor:pointer;">In báo cáo</button>
    </div>

    <section>
        <h2>Chỉ số chính</h2>
        <div class="grid">
            <?php foreach ($kpiCards as $label => $value): ?>
                <div class="card">
                    <p><?= htmlspecialchars($label) ?></p>
                    <h3><?= htmlspecialchars($value) ?></h3>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section style="margin-top:32px;">
        <h2>Thông tin Booking</h2>
        <div class="grid">
            <div class="card">
                <p>Tổng hành khách</p>
                <h3><?= number_format((int)($bookingStats['total_passengers'] ?? 0)) ?></h3>
                <div class="muted">Bao gồm toàn bộ tour</div>
            </div>
            <div class="card">
                <p>Đã huỷ</p>
                <h3><?= number_format((int)($bookingStats['cancelled_bookings'] ?? 0)) ?></h3>
                <div class="muted">Tỷ lệ <?= $bookingStats['cancel_rate'] ?? 0 ?>%</div>
            </div>
            <div class="card">
                <p>Tour đang hoạt động</p>
                <h3><?= number_format($activeTours) ?></h3>
                <div class="muted">Fill rate trung bình <?= $avgFillRate ?>%</div>
            </div>
        </div>
    </section>

    <section style="margin-top:32px;">
        <h2>Doanh thu theo tháng</h2>
        <table>
            <thead>
                <tr>
                    <th>Tháng</th>
                    <th class="text-right">Doanh thu (VND)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($revenueByMonth as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['month']) ?></td>
                        <td class="text-right"><?= number_format((float)$row['total_revenue'], 0, '.', ',') ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($revenueByMonth)): ?>
                    <tr><td colspan="2" class="text-center muted">Chưa có dữ liệu</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>

    <section style="margin-top:32px;">
        <h2>Top tour theo doanh thu</h2>
        <table>
            <thead>
                <tr>
                    <th>Tour</th>
                    <th class="text-right">Doanh thu</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($revenueByTour as $tour): ?>
                    <tr>
                        <td><?= htmlspecialchars($tour['tour_name']) ?></td>
                        <td class="text-right"><?= number_format((float)$tour['total_revenue'], 0, '.', ',') ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($revenueByTour)): ?>
                    <tr><td colspan="2" class="text-center muted">Chưa có dữ liệu</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>

    <section style="margin-top:32px;">
        <h2>Hiệu suất theo tour</h2>
        <table>
            <thead>
                <tr>
                    <th>Tour</th>
                    <th class="text-right">Ghế</th>
                    <th class="text-right">Khách</th>
                    <th class="text-right">Fill rate</th>
                    <th class="text-right">Doanh thu</th>
                    <th class="text-right">Chi phí (ước)</th>
                    <th class="text-right">Lợi nhuận</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tourEfficiency as $tour): ?>
                    <tr>
                        <td><?= htmlspecialchars($tour['tour_name']) ?></td>
                        <td class="text-right"><?= number_format((int)$tour['total_seats']) ?></td>
                        <td class="text-right"><?= number_format((int)$tour['total_passengers']) ?></td>
                        <td class="text-right"><?= $tour['fill_rate'] ?>%</td>
                        <td class="text-right"><?= number_format((float)$tour['total_revenue'], 0, '.', ',') ?></td>
                        <td class="text-right"><?= number_format((float)$tour['cost'], 0, '.', ',') ?></td>
                        <td class="text-right"><?= number_format((float)$tour['profit'], 0, '.', ',') ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($tourEfficiency)): ?>
                    <tr><td colspan="7" class="text-center muted">Chưa có dữ liệu tour</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</body>
</html>
