<?php
class DailyLog extends BaseModel
{
    protected $table = "daily_log";

    // Lấy tất cả log theo schedule_id
    public function getLogsBySchedule($schedule_id)
    {
        $sql = "SELECT dl.*, u.full_name as guide_name 
                FROM daily_log dl
                JOIN guide g ON dl.guide_id = g.guide_id
                JOIN users u ON g.user_id = u.user_id
                WHERE dl.schedule_id = ?
                ORDER BY dl.log_id ASC";

        $stm = $this->db->prepare($sql);
        $stm->execute([$schedule_id]);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm nhật ký ngày
    public function addLog($guide_id, $schedule_id, $content, $incident = null, $resolution = null)
    {
        $sql = "INSERT INTO daily_log (guide_id, schedule_id, log_date, content, incident, resolution) 
                VALUES (?, ?, CURDATE(), ?, ?, ?)";

        $stm = $this->db->prepare($sql);
        return $stm->execute([$guide_id, $schedule_id, $content, $incident, $resolution]);
    }

    // Cập nhật nhật ký
    public function updateLog($log_id, $content, $incident = null, $resolution = null)
    {
        $sql = "UPDATE daily_log 
                SET content = ?, incident = ?, resolution = ?
                WHERE log_id = ?";

        $stm = $this->db->prepare($sql);
        return $stm->execute([$content, $incident, $resolution, $log_id]);
    }

    // Lấy log theo ID (nếu cần sửa)
    public function find($log_id)
    {
        $sql = "SELECT * FROM daily_log WHERE log_id = ?";
        $stm = $this->db->prepare($sql);
        $stm->execute([$log_id]);
        return $stm->fetch(PDO::FETCH_ASSOC);
    }

    // Xóa nhật ký
    public function deleteLog($log_id)
    {
        $sql = "DELETE FROM daily_log WHERE log_id = ?";
        $stm = $this->db->prepare($sql);
        return $stm->execute([$log_id]);
    }
    
    // Get log with guide and schedule information
    public function getLogDetails($log_id)
    {
        $sql = "SELECT dl.*, u.full_name as guide_name, s.depart_date, t.tour_name
                FROM daily_log dl
                JOIN guide g ON dl.guide_id = g.guide_id
                JOIN users u ON g.user_id = u.user_id
                JOIN tour_schedule s ON dl.schedule_id = s.schedule_id
                JOIN tour t ON s.tour_id = t.tour_id
                WHERE dl.log_id = ?";

        $stm = $this->db->prepare($sql);
        $stm->execute([$log_id]);
        return $stm->fetch(PDO::FETCH_ASSOC);
    }
    
    // Get logs grouped by date for a schedule
    public function getLogsGroupedByDate($schedule_id)
    {
        $sql = "SELECT DATE(dl.created_at) as log_date, 
                       GROUP_CONCAT(CONCAT(u.full_name, ': ', dl.content) SEPARATOR '<br>') as logs
                FROM daily_log dl
                JOIN guide g ON dl.guide_id = g.guide_id
                JOIN users u ON g.user_id = u.user_id
                WHERE dl.schedule_id = ?
                GROUP BY DATE(dl.created_at)
                ORDER BY log_date ASC";

        $stm = $this->db->prepare($sql);
        $stm->execute([$schedule_id]);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Get all logs with tour and guide information for centralized view
    public function getAllLogsForDiary()
    {
        $sql = "SELECT dl.*, u.full_name as guide_name, s.depart_date, t.tour_name, t.tour_code
                FROM daily_log dl
                JOIN guide g ON dl.guide_id = g.guide_id
                JOIN users u ON g.user_id = u.user_id
                JOIN tour_schedule s ON dl.schedule_id = s.schedule_id
                JOIN tour t ON s.tour_id = t.tour_id
                ORDER BY dl.created_at DESC";

        $stm = $this->db->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
}