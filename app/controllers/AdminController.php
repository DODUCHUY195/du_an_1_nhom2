<?php

class AdminController
{
    public function dashboard()
    {
        // Nếu DashboardModel chưa tồn tại thì load dashboard cũ để tránh lỗi
        if (!class_exists('DashboardModel')) {
            require './views/dashboard.php';
            return;
        }

        $model = new DashboardModel();

        // Lấy dữ liệu cho dashboard
        $revenueByTour  = $model->getRevenueByTour();
        $revenueByMonth = $model->getRevenueByMonth();
        $bookingStats   = $model->getBookingStats();
        $tourEfficiency = $model->getTourEfficiency();

        // Tổng doanh thu
        $totalRevenue = 0;
        foreach ($revenueByTour as $row) {
            $totalRevenue += (float)($row['total_revenue'] ?? 0);
        }

        // Tổng lợi nhuận (cộng profit từng tour)
        $totalProfit = 0;
        foreach ($tourEfficiency as $row) {
            $totalProfit += (float)($row['profit'] ?? 0);
        }

        // Các KPI chính
        $kpi = [
            'total_revenue'    => $totalRevenue,
            'total_passengers' => isset($bookingStats['total_passengers']) ? (int)$bookingStats['total_passengers'] : 0,
            'cancel_rate'      => isset($bookingStats['cancel_rate']) ? (float)$bookingStats['cancel_rate'] : 0,
            'total_profit'     => $totalProfit,
        ];

        // Các biến $kpi, $revenueByTour, $revenueByMonth, $bookingStats, $tourEfficiency
        // sẽ được dùng trong views/dashboard.php
        require './views/dashboard.php';
    }

    public function exportPdf()
    {
        if (!class_exists('DashboardModel')) {
            echo 'DashboardModel not available';
            return;
        }

        $model = new DashboardModel();

        $revenueByTour  = $model->getRevenueByTour();
        $revenueByMonth = $model->getRevenueByMonth();
        $bookingStats   = $model->getBookingStats();
        $tourEfficiency = $model->getTourEfficiency();

        $totalRevenue = 0;
        foreach ($revenueByTour as $row) {
            $totalRevenue += (float)($row['total_revenue'] ?? 0);
        }

        $totalProfit = 0;
        foreach ($tourEfficiency as $row) {
            $totalProfit += (float)($row['profit'] ?? 0);
        }

        $kpi = [
            'total_revenue'    => $totalRevenue,
            'total_passengers' => isset($bookingStats['total_passengers']) ? (int)$bookingStats['total_passengers'] : 0,
            'cancel_rate'      => isset($bookingStats['cancel_rate']) ? (float)$bookingStats['cancel_rate'] : 0,
            'total_profit'     => $totalProfit,
        ];

        // View in-friendly để in ra PDF (Ctrl+P → Save as PDF)
        // Nếu bot VSCode tạo file tên khác, bạn chỉnh lại đường dẫn cho khớp.
        require './views/exportPdf.php';
    }

    // (tuỳ chọn) xuất Excel – nếu bạn đã thêm route cho nó
    public function exportExcel()
    {
        if (!class_exists('DashboardModel')) {
            echo 'DashboardModel not available';
            return;
        }

        $model = new DashboardModel();
        $tourEfficiency = $model->getTourEfficiency();

        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="dashboard_report_' . date('Ymd_His') . '.xls"');
        header('Pragma: no-cache');
        header('Expires: 0');

        echo "Tour\tTotal seats\tPassengers\tFill rate (%)\tRevenue\tCost\tProfit\n";

        foreach ($tourEfficiency as $row) {
            $line = [
                $row['tour_name'],
                (int)$row['total_seats'],
                (int)$row['total_passengers'],
                $row['fill_rate'],
                $row['total_revenue'],
                $row['cost'],
                $row['profit'],
            ];
            echo implode("\t", $line) . "\n";
        }

        exit;
    }
}
