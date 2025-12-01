<?php require_once "./views/layouts/admin/header.php"; ?>
<?php require_once "./views/layouts/admin/sidebar.php"; ?>

<main class="relative h-full max-h-screen transition-all xl:ml-68 rounded-xl pt-20">
    <?php require_once "./views/layouts/admin/navbar.php"; ?>

    <div class="w-full px-6 py-6 mx-auto">

                <!-- Tiêu đề + nút hành động -->
        <div class="flex flex-wrap items-center justify-between mb-6">
            <div>
                <h2 class="mb-1 text-2xl font-bold text-slate-700 dark:text-white">
                    Tổng quan doanh thu
                </h2>
                <p class="text-sm text-slate-500">
                    Cập nhật tới ngày <?= date('d/m/Y') ?>
                </p>
            </div>

            <div class="flex gap-2">
                <!-- Nút quản lý booking -->
                <a href="?route=/bookings"
                   style="padding:8px 14px;border-radius:8px;background:#3b82f6;color:#fff;
                          font-size:14px;font-weight:600;display:inline-block;text-decoration:none;">
                    Quản lý booking
                </a>

                <!-- Nút xuất Excel -->
                <a href="?route=/admin/exportExcel"
                   style="padding:8px 14px;border-radius:8px;background:#22c55e;color:#fff;
                          font-size:14px;font-weight:600;display:inline-block;text-decoration:none;">
                    Xuất Excel
                </a>

                <!-- Nút xuất PDF -->
                <a href="?route=/admin/exportPdf" target="_blank"
                   style="padding:8px 14px;border-radius:8px;background:#4b5563;color:#fff;
                          font-size:14px;font-weight:600;display:inline-block;text-decoration:none;">
                    Xuất PDF
                </a>
            </div>
        </div>


        <?php
        // Đảm bảo luôn có mảng kpi để không bị warning
        $kpi = $kpi ?? [
            'total_revenue'    => 0,
            'total_passengers' => 0,
            'cancel_rate'      => 0,
            'total_profit'     => 0,
        ];

        $cards = [
            [
                'label' => 'Tổng doanh thu',
                'value' => number_format($kpi['total_revenue']),
                'suffix'=> ' đ',
            ],
            [
                'label' => 'Tổng số khách',
                'value' => number_format($kpi['total_passengers']),
                'suffix'=> ' khách',
            ],
            [
                'label' => 'Tỷ lệ hủy',
                'value' => $kpi['cancel_rate'],
                'suffix'=> ' %',
            ],
            [
                'label' => 'Lợi nhuận ước tính',
                'value' => number_format($kpi['total_profit']),
                'suffix'=> ' đ',
            ],
        ];
        ?>

        <!-- KPI cards -->
        <div class="flex flex-wrap -mx-3 mb-6">
            <?php foreach ($cards as $card): ?>
                <div class="w-full px-3 mb-6 sm:w-6 md:w-3/12">
                    <div
                        class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border dark:bg-slate-850">
                        <div class="p-4">
                            <p class="mb-1 text-sm font-semibold text-slate-500 dark:text-slate-300">
                                <?= htmlspecialchars($card['label']) ?>
                            </p>
                            <h5 class="text-xl font-bold dark:text-white">
                                <?= htmlspecialchars($card['value'] . $card['suffix']) ?>
                            </h5>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Hàng 1: Doanh thu theo tháng + Tỷ lệ hủy -->
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full px-3 mb-6 lg:w-8/12 lg:mb-0">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border dark:bg-slate-850">
                    <div class="p-4 pb-0">
                        <h6 class="text-base font-semibold dark:text-white">Doanh thu theo tháng</h6>
                    </div>
                    <div class="p-4">
                        <canvas id="chartRevenueByMonth" height="300"></canvas>
                    </div>
                </div>
            </div>

            <div class="w-full px-3 lg:w-4/12">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border dark:bg-slate-850">
                    <div class="p-4 pb-0">
                        <h6 class="text-base font-semibold dark:text-white">Tỷ lệ hủy booking</h6>
                    </div>
                    <div class="p-4">
                        <canvas id="chartCancelRate" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hàng 2: Doanh thu theo tour -->
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full px-3">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border dark:bg-slate-850">
                    <div class="p-4 pb-0">
                        <h6 class="text-base font-semibold dark:text-white">Top tour theo doanh thu</h6>
                    </div>
                    <div class="p-4">
                        <canvas id="chartRevenueByTour" height="320"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bảng hiệu quả tour -->
        <div class="flex flex-wrap -mx-3">
            <div class="w-full px-3">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border dark:bg-slate-850">
                    <div class="p-4 pb-0 flex items-center justify-between">
                        <h6 class="text-base font-semibold dark:text-white">
                            Hiệu quả tour (lấp đầy / doanh thu / lợi nhuận)
                        </h6>
                    </div>
                    <div class="p-4">
                        <div class="overflow-x-auto">
                            <table class="items-center w-full mb-0 align-top border-collapse text-slate-500">
                                <thead>
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase">Tour</th>
                                    <th class="px-3 py-2 text-right text-xs font-semibold uppercase">Tổng ghế</th>
                                    <th class="px-3 py-2 text-right text-xs font-semibold uppercase">Khách đã đặt</th>
                                    <th class="px-3 py-2 text-right text-xs font-semibold uppercase">% Lấp đầy</th>
                                    <th class="px-3 py-2 text-right text-xs font-semibold uppercase">Doanh thu</th>
                                    <th class="px-3 py-2 text-right text-xs font-semibold uppercase">Chi phí</th>
                                    <th class="px-3 py-2 text-right text-xs font-semibold uppercase">Lợi nhuận</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($tourEfficiency)): ?>
                                    <?php foreach ($tourEfficiency as $row): ?>
                                        <tr>
                                            <td class="px-3 py-2 text-sm">
                                                <?= htmlspecialchars($row['tour_name'] ?? '') ?>
                                            </td>
                                            <td class="px-3 py-2 text-sm text-right">
                                                <?= (int)($row['total_seats'] ?? 0) ?>
                                            </td>
                                            <td class="px-3 py-2 text-sm text-right">
                                                <?= (int)($row['total_passengers'] ?? 0) ?>
                                            </td>
                                            <td class="px-3 py-2 text-sm text-right">
                                                <?= number_format((float)($row['fill_rate'] ?? 0), 2) ?>%
                                            </td>
                                            <td class="px-3 py-2 text-sm text-right">
                                                <?= number_format((float)($row['total_revenue'] ?? 0)) ?>
                                            </td>
                                            <td class="px-3 py-2 text-sm text-right">
                                                <?= number_format((float)($row['cost'] ?? 0)) ?>
                                            </td>
                                            <td class="px-3 py-2 text-sm text-right">
                                                <?= number_format((float)($row['profit'] ?? 0)) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="px-3 py-4 text-sm text-center">
                                            Chưa có dữ liệu tour để thống kê.
                                        </td>
                                    </tr>
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

<?php require_once "./views/layouts/admin/footer.php"; ?>

<script>
window.addEventListener('load', function () {
    if (typeof Chart === 'undefined') {
        console.warn('Chart.js chưa sẵn sàng');
        return;
    }

    const revenueByMonth  = <?= json_encode($revenueByMonth ?? []) ?>;
    const revenueByTour   = <?= json_encode($revenueByTour ?? []) ?>;
    const bookingStats    = <?= json_encode($bookingStats ?? []) ?>;

    // Doanh thu theo tháng
    (function () {
        const el = document.getElementById('chartRevenueByMonth');
        if (!el) return;

        const labels = revenueByMonth.map(r => r.month);
        const data   = revenueByMonth.map(r => Number(r.total_revenue || 0));

        new Chart(el.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Doanh thu (đ)',
                    data: data
                }]
            },
            options: {
                responsive: true,
                scales: {y: {beginAtZero: true}}
            }
        });
    })();

    // Doanh thu theo tour
    (function () {
        const el = document.getElementById('chartRevenueByTour');
        if (!el) return;

        const labels = revenueByTour.map(r => r.tour_name);
        const data   = revenueByTour.map(r => Number(r.total_revenue || 0));

        new Chart(el.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Doanh thu (đ)',
                    data: data
                }]
            },
            options: {
                responsive: true,
                scales: {y: {beginAtZero: true}}
            }
        });
    })();

    // Pie chart tỉ lệ hủy
    (function () {
        const el = document.getElementById('chartCancelRate');
        if (!el) return;

        const total      = Number(bookingStats.total_bookings || 0);
        const cancelled  = Number(bookingStats.cancelled_bookings || 0);
        const success    = Math.max(total - cancelled, 0);

        new Chart(el.getContext('2d'), {
            type: 'pie',
            data: {
                labels: ['Hủy', 'Không hủy'],
                datasets: [{
                    data: [cancelled, success]
                }]
            },
            options: {
                responsive: true
            }
        });
    })();
});
</script>
