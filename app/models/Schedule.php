<?php

class Schedule extends BaseModel
{
    public function allWithTour(): array
    {
        $sql = "SELECT s.*, t.tour_name, t.price,
                       GREATEST(s.seats_total - s.seats_booked, 0) AS available_seats
                  FROM tour_schedule s
                  JOIN tour t ON s.tour_id = t.tour_id
              ORDER BY s.depart_date DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function all()
    {
        return $this->allWithTour();
    }

    public function find($id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM tour_schedule WHERE schedule_id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    public function create($data)
    {
        $sql = 'INSERT INTO tour_schedule (tour_id, depart_date, meeting_point, seats_total, seats_booked, status)
                VALUES (:tour_id, :depart_date, :meeting_point, :seats_total, :seats_booked, :status)';

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':tour_id'       => $data['tour_id'],
            ':depart_date'   => $data['depart_date'],
            ':meeting_point' => $data['meeting_point'] ?? null,
            ':seats_total'   => $data['seats_total'],
            ':seats_booked'  => $data['seats_booked'] ?? 0,
            ':status'        => $data['status'] ?? 'open',
        ]);
    }

    public function update($id, $data)
    {
        $sql = 'UPDATE tour_schedule
                   SET tour_id = :tour_id,
                       depart_date = :depart_date,
                       meeting_point = :meeting_point,
                       seats_total = :seats_total,
                       seats_booked = :seats_booked,
                       status = :status
                 WHERE schedule_id = :id';

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':tour_id'       => $data['tour_id'],
            ':depart_date'   => $data['depart_date'],
            ':meeting_point' => $data['meeting_point'] ?? null,
            ':seats_total'   => $data['seats_total'],
            ':seats_booked'  => $data['seats_booked'] ?? 0,
            ':status'        => $data['status'] ?? 'open',
            ':id'            => $id,
        ]);
    }

    public function getAvailableSeats(int $scheduleId): int
    {
        $stmt = $this->db->prepare('SELECT seats_total, seats_booked FROM tour_schedule WHERE schedule_id = :id LIMIT 1');
        $stmt->execute([':id' => $scheduleId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return 0;
        }

        $available = (int)$row['seats_total'] - (int)$row['seats_booked'];
        return $available > 0 ? $available : 0;
    }

    public function adjustSeatsBooked(int $scheduleId, int $delta): bool
    {
        $sql = 'UPDATE tour_schedule
                   SET seats_booked = seats_booked + :delta
                 WHERE schedule_id = :id
                   AND seats_booked + :delta >= 0
                   AND seats_booked + :delta <= seats_total';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':delta', $delta, PDO::PARAM_INT);
        $stmt->bindValue(':id', $scheduleId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}

?>