<?php
class Booking extends BaseModel
{

    public function searchBookings(array $filters): array
    {
        $sql = "SELECT 
                b.booking_id,
                b.booking_code,
                b.booking_date,
                b.total_amount,
                b.deposit,
                b.payment_method,
                b.status,
                b.passenger_count,
                a.full_name AS customer_name,
                a.email,
                a.phone,
                ts.schedule_id,
                ts.depart_date,
                ts.return_date,
                t.tour_id,
                t.tour_name,
                t.tour_code
            FROM booking b
            JOIN account a ON a.account_id = b.customer_id
            JOIN tour_schedule ts ON ts.schedule_id = b.schedule_id
            JOIN tour t ON t.tour_id = ts.tour_id
            WHERE 1=1";

        $params = [];

        if (!empty($filters['tour_id'])) {
            $sql .= " AND t.tour_id = :tour_id";
            $params['tour_id'] = $filters['tour_id'];
        }

        if (!empty($filters['schedule_id'])) {
            $sql .= " AND ts.schedule_id = :schedule_id";
            $params['schedule_id'] = $filters['schedule_id'];
        }

        if (!empty($filters['status'])) {
            $sql .= " AND b.status = :status";
            $params['status'] = $filters['status'];
        }

        $sql .= " ORDER BY b.booking_date DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }




    public function getByCustomer($customerId)
    {
        $sql = "
            SELECT b.*, 
                   s.depart_date,
                   s.return_date,
                   t.tour_name, t.price
            FROM booking b
            LEFT JOIN tour_schedule s ON b.schedule_id = s.schedule_id
            LEFT JOIN tour t ON s.tour_id = t.tour_id
            WHERE b.customer_id = ?
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$customerId]);
        return $stmt->fetchAll();
    }


    // Lấy danh sách tất cả booking kèm thông tin tour và lịch
    public function getAllBookings(): array
    {
        $sql = "SELECT 
                    b.booking_id,
                    b.customer_id,
                    a.full_name AS customer_name,
                    a.email,
                    a.phone,
                    t.tour_name,
                    ts.depart_date,
                    ts.return_date,
                    b.passenger_count,
                    b.total_amount,
                    b.deposit,
                    b.payment_method,
                    b.status,
                    b.booking_date,
                    b.booking_code
                FROM booking b
                JOIN account a ON a.account_id = b.customer_id
                JOIN tour_schedule ts ON ts.schedule_id = b.schedule_id
                JOIN tour t ON t.tour_id = ts.tour_id
                ORDER BY b.booking_date DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getAllWithRelations(array $filters = []): array
    {
                $sql = "SELECT b.*, 
                                             t.tour_name, t.tour_code,
                                             s.depart_date, s.return_date, s.seats_total, s.seats_booked,
                                             a.full_name AS customer_name, a.email, a.phone,
                                             v.vehicle_name, v.plate_number, v.total_seats, v.booked_seats,
                                             (v.total_seats - v.booked_seats) AS vehicle_available_seats,
                                             (
                                                     SELECT COUNT(*)
                                                     FROM vehicles v2
                                                     WHERE v2.schedule_id = s.schedule_id
                                                         AND v2.total_seats > v2.booked_seats
                                             ) AS available_vehicles
			  FROM booking b
			  LEFT JOIN tour_schedule s ON b.schedule_id = s.schedule_id
			  LEFT JOIN tour t ON s.tour_id = t.tour_id
			  LEFT JOIN account a ON b.customer_id = a.account_id
                                    LEFT JOIN vehicles v ON v.vehicle_id = b.vehicle_id
			 WHERE 1=1";

        $params = [];

        if (!empty($filters['tour_id'])) {
            $sql .= ' AND t.tour_id = :tour_id';
            $params[':tour_id'] = (int)$filters['tour_id'];
        }

        if (!empty($filters['schedule_id'])) {
            $sql .= ' AND s.schedule_id = :schedule_id';
            $params[':schedule_id'] = (int)$filters['schedule_id'];
        }

        if (!empty($filters['status'])) {
            $sql .= ' AND b.status = :status';
            $params[':status'] = trim($filters['status']);
        }

        $sql .= ' ORDER BY b.booking_date DESC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }













    public function getPassengerCount(int $bookingId): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM tour_passenger WHERE booking_id = :bid');
        $stmt->execute([':bid' => $bookingId]);
        $count = $stmt->fetchColumn();

        return $count !== false ? (int)$count : 0;
    }

    // Cập nhật số lượng khách trong bảng booking
    public function updatePassengerCount(int $bookingId, int $count): bool
    {
        $sql = "UPDATE booking SET passenger_count = :count WHERE booking_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'count' => $count,
            'id'    => $bookingId
        ]);
    }


    public function create(array $data): int
    {
        $sql = "INSERT INTO booking (
                customer_id,
                schedule_id,
                vehicle_id,
                booking_date,
                total_amount,
                deposit,
                payment_method,
                status,
                booking_source,
                passenger_count
            ) VALUES (
                :customer_id,
                :schedule_id,
                :vehicle_id,
                CURRENT_TIMESTAMP,
                :total_amount,
                :deposit,
                :payment_method,
                :status,
                :booking_source,
                :passenger_count
            )";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'customer_id'    => $data['customer_id'],
            'schedule_id'    => $data['schedule_id'],
            'vehicle_id'     => $data['vehicle_id'] ?? null,
            'total_amount'   => $data['total_amount'],
            'deposit'        => $data['deposit'],
            'payment_method' => $data['payment_method'],
            'status'         => $data['status'],
            'booking_source' => $data['booking_source'],
            'passenger_count'=> $data['passenger_count'],
        ]);

        $bookingId = (int)$this->db->lastInsertId();

        $code = 'BK' . str_pad($bookingId, 6, '0', STR_PAD_LEFT);
        $this->db->prepare("UPDATE booking SET booking_code = :code WHERE booking_id = :id")
                 ->execute(['code' => $code, 'id' => $bookingId]);

        return $bookingId;
    }


    public function addPassengers(int $bookingId, int $passengerCount): bool
    {
        // Ví dụ: chèn bản ghi vào tour_passenger với số lượng khách
        $sql = "INSERT INTO tour_passenger (booking_id) VALUES (:booking_id)";
        $stmt = $this->db->prepare($sql);
        for ($i = 0; $i < $passengerCount; $i++) {
            if (!$stmt->execute(['booking_id' => $bookingId])) return false;
        }
        return true;
    }

    public function findById(int $bookingId)
    {
        $stmt = $this->db->prepare("SELECT * FROM booking WHERE booking_id = :id");
        $stmt->execute(['id' => $bookingId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateStatus(int $bookingId, string $status): bool
    {
        $stmt = $this->db->prepare("UPDATE booking SET status = :status WHERE booking_id = :id");
        return $stmt->execute(['status' => $status, 'id' => $bookingId]);
    }

    public function hasDuplicate(int $customerId, int $scheduleId): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM booking WHERE customer_id = :cid AND schedule_id = :sid");
        $stmt->execute(['cid' => $customerId, 'sid' => $scheduleId]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function countBySource(): array
    {
        $stmt = $this->db->query("SELECT booking_source, COUNT(*) AS total FROM booking GROUP BY booking_source");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = ['admin' => 0, 'customer' => 0];
        foreach ($rows as $row) {
            if (!empty($row['booking_source'])) {
                $result[$row['booking_source']] = (int)$row['total'];
            }
        }

        return $result;
    }
}
