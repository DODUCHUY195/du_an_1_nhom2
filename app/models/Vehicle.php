<?php
class Vehicle extends BaseModel
{
    protected $table = 'vehicles';

    public function getBySchedule(int $scheduleId): array
    {
        $sql = "SELECT v.*, (v.total_seats - v.booked_seats) AS available_seats
                FROM vehicles v
                WHERE v.schedule_id = :schedule_id
                ORDER BY v.vehicle_name";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['schedule_id' => $scheduleId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function increaseBookedSeats(int $vehicleId, int $count): bool
    {
        $sql = "UPDATE vehicles SET booked_seats = booked_seats + :count WHERE vehicle_id = :vehicle_id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'count' => $count,
            'vehicle_id' => $vehicleId,
        ]);
    }

    public function decreaseBookedSeats(int $vehicleId, int $count): bool
    {
        $sql = "UPDATE vehicles SET booked_seats = GREATEST(booked_seats - :count, 0) WHERE vehicle_id = :vehicle_id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'count' => $count,
            'vehicle_id' => $vehicleId,
        ]);
    }

    public function countAvailableVehiclesBySchedule(int $scheduleId): int
    {
        $sql = "SELECT COUNT(*) FROM vehicles WHERE schedule_id = :schedule_id AND total_seats > booked_seats";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['schedule_id' => $scheduleId]);

        return (int) $stmt->fetchColumn();
    }
}
