<?php
class GuideAssignment extends BaseModel
{
    protected $table = "guide_assignment";

    // Gán hướng dẫn viên cho lịch trình
    public function assignGuide($schedule_id, $guide_id)
    {
        // Check nếu đã có gán rồi => cập nhật
        $sqlCheck = "SELECT * FROM guide_assignment WHERE schedule_id = ?";
        $stm = $this->db->prepare($sqlCheck);
        $stm->execute([$schedule_id]);
        $exists = $stm->fetch(PDO::FETCH_ASSOC);

        if ($exists) {
            // Cập nhật lại guide_id
            $sqlUpdate = "UPDATE guide_assignment SET guide_id = ? WHERE schedule_id = ?";
            $stm2 = $this->db->prepare($sqlUpdate);
            return $stm2->execute([$guide_id, $schedule_id]);
        }

        // Nếu chưa có => tạo mới
        $sqlInsert = "INSERT INTO guide_assignment (schedule_id, guide_id)
                      VALUES (?, ?)";
        $stm3 = $this->db->prepare($sqlInsert);
        return $stm3->execute([$schedule_id, $guide_id]);
    }

    // Lấy hướng dẫn viên của 1 schedule
    public function getAssignedGuide($schedule_id)
    {
        $sql = "SELECT g.*, u.full_name as guide_name FROM guide_assignment ga 
                JOIN guide g ON ga.guide_id = g.guide_id
                JOIN users u ON g.user_id = u.user_id
                WHERE ga.schedule_id = ?";

        $stm = $this->db->prepare($sql);
        $stm->execute([$schedule_id]);
        return $stm->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy tất cả hướng dẫn viên được phân công cho 1 schedule
    public function getBySchedule($schedule_id)
    {
        $sql = "SELECT ga.*, g.guide_id, u.full_name as guide_name, u.full_name as user_name 
                FROM guide_assignment ga
                JOIN guide g ON ga.guide_id = g.guide_id
                JOIN users u ON g.user_id = u.user_id
                WHERE ga.schedule_id = ?";

        $stm = $this->db->prepare($sql);
        $stm->execute([$schedule_id]);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách lịch trình + hướng dẫn viên (dashboard)
    public function getAllAssignments()
    {
        $sql = "SELECT 
                    s.schedule_id,
                    s.depart_date,
                    t.tour_name,
                    g.user_id as guide_name
                FROM tour_schedule s
                LEFT JOIN guide_assignment ga ON s.schedule_id = ga.schedule_id
                LEFT JOIN guide g ON ga.guide_id = g.guide_id
                LEFT JOIN tour t ON s.tour_id = t.tour_id
                ORDER BY s.schedule_id DESC";

        $stm = $this->db->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    // Xóa phân công hướng dẫn viên
    public function remove($assignment_id)
    {
        $sql = "DELETE FROM guide_assignment WHERE assignment_id = ?";
        $stm = $this->db->prepare($sql);
        return $stm->execute([$assignment_id]);
    }
    
    // Get all guides with their assignment status for a specific schedule
    public function getGuidesWithAssignmentStatus($schedule_id)
    {
        $sql = "SELECT g.*, u.full_name as guide_name,
                       CASE 
                           WHEN ga.schedule_id IS NOT NULL THEN 1 
                           ELSE 0 
                       END as is_assigned
                FROM guide g
                JOIN users u ON g.user_id = u.user_id
                LEFT JOIN guide_assignment ga ON g.guide_id = ga.guide_id AND ga.schedule_id = ?
                ORDER BY g.guide_id";

        $stm = $this->db->prepare($sql);
        $stm->execute([$schedule_id]);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Get assignment details by ID
    public function getById($assignment_id)
    {
        $sql = "SELECT ga.*, g.guide_id, u.full_name as guide_name, u.full_name as user_name, s.depart_date, t.tour_name
                FROM guide_assignment ga
                JOIN guide g ON ga.guide_id = g.guide_id
                JOIN users u ON g.user_id = u.user_id
                JOIN tour_schedule s ON ga.schedule_id = s.schedule_id
                JOIN tour t ON s.tour_id = t.tour_id
                WHERE ga.assignment_id = ?";

        $stm = $this->db->prepare($sql);
        $stm->execute([$assignment_id]);
        return $stm->fetch(PDO::FETCH_ASSOC);
    }
}