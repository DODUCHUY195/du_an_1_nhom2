<?php
class DashboardModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getRevenueByTour(): array
    {
        $sql = "SELECT 
                    t.tour_id,
                    t.tour_name,
                    COALESCE(SUM(CASE WHEN b.status != 'cancelled' THEN b.total_amount ELSE 0 END), 0) AS total_revenue
                FROM tour t
                JOIN tour_schedule s ON s.tour_id = t.tour_id
                LEFT JOIN booking b ON b.schedule_id = s.schedule_id
                GROUP BY t.tour_id, t.tour_name
                ORDER BY total_revenue DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function getRevenueByMonth(): array
    {
        $sql = "SELECT 
                    DATE_FORMAT(b.booking_date, '%Y-%m') AS month,
                    COALESCE(SUM(CASE WHEN b.status != 'cancelled' THEN b.total_amount ELSE 0 END), 0) AS total_revenue
                FROM booking b
                GROUP BY DATE_FORMAT(b.booking_date, '%Y-%m')
                ORDER BY month";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function getBookingStats(): array
    {
        $sql = "SELECT
                    COUNT(*) AS total_bookings,
                    SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) AS cancelled_bookings,
                    COALESCE(SUM(passenger_count), 0) AS total_passengers
                FROM booking";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC) ?: [
            'total_bookings' => 0,
            'cancelled_bookings' => 0,
            'total_passengers' => 0,
        ];

        $total = (int)($result['total_bookings'] ?? 0);
        $cancelled = (int)($result['cancelled_bookings'] ?? 0);
        $result['total_bookings'] = $total;
        $result['cancelled_bookings'] = $cancelled;
        $result['total_passengers'] = (int)($result['total_passengers'] ?? 0);
        $result['cancel_rate'] = $total > 0 ? round(($cancelled / $total) * 100, 2) : 0;

        return $result;
    }

    public function getTourEfficiency(): array
    {
        $sql = "SELECT 
                    t.tour_id,
                    t.tour_name,
                    COALESCE(SUM(s.seats_total), 0) AS total_seats,
                    COALESCE(SUM(CASE WHEN b.status != 'cancelled' THEN b.passenger_count ELSE 0 END), 0) AS total_passengers,
                    COALESCE(SUM(CASE WHEN b.status != 'cancelled' THEN b.total_amount ELSE 0 END), 0) AS total_revenue
                FROM tour t
                JOIN tour_schedule s ON s.tour_id = t.tour_id
                LEFT JOIN booking b ON b.schedule_id = s.schedule_id
                GROUP BY t.tour_id, t.tour_name";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        foreach ($rows as &$row) {
            $totalSeats = (int)($row['total_seats'] ?? 0);
            $totalPassengers = (int)($row['total_passengers'] ?? 0);
            $totalRevenue = (float)($row['total_revenue'] ?? 0);

            $row['total_seats'] = $totalSeats;
            $row['total_passengers'] = $totalPassengers;
            $row['total_revenue'] = $totalRevenue;
            $row['fill_rate'] = $totalSeats > 0 ? round(($totalPassengers / $totalSeats) * 100, 2) : 0;
            $row['cost'] = round($totalRevenue * 0.7, 2);
            $row['profit'] = round($totalRevenue - $row['cost'], 2);
        }
        unset($row);

        return $rows;
    }
}
