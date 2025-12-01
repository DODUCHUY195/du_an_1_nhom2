<?php
class Guide extends BaseModel {
    
    public function all(){
        // Fixed: Removed ORDER BY created_at since it doesn't exist in the guide table
        $stmt = $this->db->query('SELECT g.*, u.full_name AS guide_name FROM guide g LEFT JOIN booking_customer u ON g.customer_id = u.customer_id');
        return $stmt->fetchAll();
    }

    // Lấy danh sách HDV kèm tour đang phụ trách
    public function getAllGuides() {
        $sql = "
            SELECT g.guide_id, g.full_name, g.birth_date, g.contact, g.license_no, g.photo, g.note,
                   ts.schedule_id, ts.depart_date, ts.meeting_point
            FROM guide g
            LEFT JOIN guide_assignment ga ON g.guide_id = ga.guide_id
            LEFT JOIN tour_schedule ts ON ga.schedule_id = ts.schedule_id
            ORDER BY g.guide_id
        ";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy chi tiết 1 HDV
    public function getGuideById($id) {
        $stmt = $this->db->prepare("SELECT * FROM guide WHERE guide_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tạo hồ sơ HDV
    public function createGuide($data) {
        $stmt = $this->db->prepare("
            INSERT INTO guide (full_name, birth_date, contact, license_no, photo, note)
            VALUES (:full_name, :birth_date, :contact, :license_no, :photo, :note)
        ");
        return $stmt->execute([
            ':full_name'    => $data['full_name'],
            ':birth_date' => $data['birth_date'],
            ':contact'    => $data['contact'],
            ':license_no' => $data['license_no'],
            ':photo'      => $data['photo'],
            ':note'       => $data['note']
        ]);
    }

    // Sửa thông tin HDV
    public function updateGuide($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE guide 
            SET full_name = :full_name,
            birth_date = :birth_date,
                contact = :contact,
                license_no = :license_no,
                photo = :photo,
                note = :note
            WHERE guide_id = :id
        ");
        return $stmt->execute([
             ':full_name' => $data['full_name'],
            ':birth_date' => $data['birth_date'],
            ':contact'    => $data['contact'],
            ':license_no' => $data['license_no'],
            ':photo'      => $data['photo'],
            ':note'       => $data['note'],
            ':id'         => $id
        ]);
    }

    // Xóa HDV
    public function deleteGuide($id) {
        $stmt = $this->db->prepare("DELETE FROM guide WHERE guide_id = ?");
        return $stmt->execute([$id]);
    }

    // Phân công HDV cho tour
    public function assignGuide($guide_id, $schedule_id) {
        $stmt = $this->db->prepare("
            INSERT INTO guide_assignment (guide_id, schedule_id, assigned_at)
            VALUES (:guide_id, :schedule_id, NOW())
        ");
        return $stmt->execute([
            ':guide_id'   => $guide_id,
            ':schedule_id'=> $schedule_id
        ]);
    }

    // Lấy danh sách tất cả người dùng
    public function getAllbooking_customer() {
        $stmt = $this->db->query("SELECT customer_id, full_name FROM booking_customer ORDER BY full_name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

     public function getAllSchedules() {
        $stmt = $this->db->query("SELECT schedule_id, depart_date, meeting_point FROM tour_schedule ORDER BY depart_date ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAssignmentByGuide($guide_id) {
    $stmt = $this->db->prepare("SELECT schedule_id FROM guide_assignment WHERE guide_id = ?");
    $stmt->execute([$guide_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
}
?>
