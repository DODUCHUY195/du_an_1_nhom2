<?php

class Booking extends BaseModel
{
	public function getAllWithRelations(array $filters = []): array
	{
		$sql = "SELECT b.*, CONCAT('BK', LPAD(b.booking_id, 6, '0')) AS booking_code,
					   t.tour_name, t.tour_code,
					   s.depart_date, s.seats_total, s.seats_booked,
					   u.full_name, u.email, u.phone
				  FROM booking b
				  LEFT JOIN tour_schedule s ON b.schedule_id = s.schedule_id
				  LEFT JOIN tour t ON s.tour_id = t.tour_id
				  LEFT JOIN users u ON b.customer_id = u.user_id
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

	public function findById(int $id): ?array
	{
		$sql = "SELECT b.*, CONCAT('BK', LPAD(b.booking_id, 6, '0')) AS booking_code,
					   t.tour_name, t.tour_code,
					   s.depart_date, s.seats_total, s.seats_booked,
					   u.full_name, u.email, u.phone
				  FROM booking b
				  LEFT JOIN tour_schedule s ON b.schedule_id = s.schedule_id
				  LEFT JOIN tour t ON s.tour_id = t.tour_id
				  LEFT JOIN users u ON b.customer_id = u.user_id
				 WHERE b.booking_id = :id
				 LIMIT 1";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([':id' => $id]);
		$result = $stmt->fetch(PDO::FETCH_ASSOC);

		return $result ?: null;
	}

	public function create(array $data): int
	{
		$sql = 'INSERT INTO booking (customer_id, schedule_id, booking_date, total_amount, deposit, payment_method, status)
				VALUES (:customer_id, :schedule_id, NOW(), :total_amount, :deposit, :payment_method, :status)';

		$stmt = $this->db->prepare($sql);
		$stmt->execute([
			':customer_id'    => $data['customer_id'],
			':schedule_id'    => $data['schedule_id'],
			':total_amount'   => $data['total_amount'],
			':deposit'        => $data['deposit'] ?? 0,
			':payment_method' => $data['payment_method'] ?? null,
			':status'         => $data['status'] ?? 'pending',
		]);

		return (int)$this->db->lastInsertId();
	}

	public function updateStatus(int $bookingId, string $status): bool
	{
		$stmt = $this->db->prepare('UPDATE booking SET status = :status WHERE booking_id = :id');
		return $stmt->execute([
			':status' => $status,
			':id'     => $bookingId,
		]);
	}

	public function hasDuplicate(int $customerId, int $scheduleId): bool
	{
		$sql = "SELECT COUNT(*)
				  FROM booking
				 WHERE customer_id = :cid
				   AND schedule_id = :sid
				   AND status IN ('pending','confirmed')";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([
			':cid' => $customerId,
			':sid' => $scheduleId,
		]);

		return (int)$stmt->fetchColumn() > 0;
	}

	public function getPassengerCount(int $bookingId): int
	{
		$stmt = $this->db->prepare('SELECT COUNT(*) FROM tour_passenger WHERE booking_id = :bid');
		$stmt->execute([':bid' => $bookingId]);
		$count = $stmt->fetchColumn();

		return $count !== false ? (int)$count : 0;
	}

	public function updatePassengerCount(int $bookingId, int $newCount): bool
	{
		if ($newCount < 0) {
			return false;
		}

		try {
			$this->db->beginTransaction();

			$currentStmt = $this->db->prepare('SELECT COUNT(*) FROM tour_passenger WHERE booking_id = :bid');
			$currentStmt->execute([':bid' => $bookingId]);
			$currentCount = (int)$currentStmt->fetchColumn();

			if ($newCount > $currentCount) {
				$insertStmt = $this->db->prepare(
					'INSERT INTO tour_passenger (booking_id, full_name, type) VALUES (:booking_id, :full_name, :type)'
				);
				$toAdd = $newCount - $currentCount;
				for ($i = 1; $i <= $toAdd; $i++) {
					$label = 'Khách ' . ($currentCount + $i);
					$insertStmt->execute([
						':booking_id' => $bookingId,
						':full_name'  => $label,
						':type'       => 'adult',
					]);
				}
			} elseif ($newCount < $currentCount) {
				$toRemove = $currentCount - $newCount;
				$selectStmt = $this->db->prepare(
					'SELECT passenger_id FROM tour_passenger WHERE booking_id = :bid ORDER BY passenger_id DESC LIMIT :lim'
				);
				$selectStmt->bindValue(':bid', $bookingId, PDO::PARAM_INT);
				$selectStmt->bindValue(':lim', $toRemove, PDO::PARAM_INT);
				$selectStmt->execute();
				$ids = $selectStmt->fetchAll(PDO::FETCH_COLUMN);

				if (!empty($ids)) {
					$placeholder = implode(',', array_fill(0, count($ids), '?'));
					$deleteStmt = $this->db->prepare("DELETE FROM tour_passenger WHERE passenger_id IN ($placeholder)");
					$deleteStmt->execute($ids);
				}
			}

			$this->db->commit();
			return true;
		} catch (Throwable $th) {
			if ($this->db->inTransaction()) {
				$this->db->rollBack();
			}
			return false;
		}
	}
}
