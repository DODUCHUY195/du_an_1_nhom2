<?php
class Booking extends BaseModel
{
    protected $table = 'booking';

    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY booking_id DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE booking_id=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($customer_id, $schedule_id, $total_amount)
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (customer_id,schedule_id,total_amount,status,booking_date) VALUES (?,?,?, 'pending', NOW())");
        $stmt->execute([$customer_id,$schedule_id,$total_amount]);
        return $this->db->lastInsertId();
    }

    public function updateStatus($booking_id, $status)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET status=? WHERE booking_id=?");
        return $stmt->execute([$status,$booking_id]);
    }

    public function updateDeposit($booking_id, $deposit)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET deposit=? WHERE booking_id=?");
        return $stmt->execute([$deposit,$booking_id]);
    }
}
