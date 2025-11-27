<?php
class Schedule extends BaseModel
{
    protected $table = "tour_schedule";

    // ==========================
    // Lấy toàn bộ schedules + join tour
    // ==========================
    public function getAll()
    {
        $sql = "SELECT s.*, t.tour_name, t.tour_code, t.image
                FROM tour_schedule s
                JOIN tour t ON s.tour_id = t.tour_id
                ORDER BY s.schedule_id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllSchedule()
    {
        return $this->getAll(); // gọi method có sẵn
    }

    // ==========================
    // Lấy schedule theo ID
    // ==========================
    public function getById($id)
    {
        $sql = "SELECT s.*, t.tour_name, t.tour_code, t.image
                FROM tour_schedule s
                JOIN tour t ON s.tour_id = t.tour_id
                WHERE s.schedule_id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ==========================
    // Lấy danh sách theo Tour ID
    // ==========================
    public function getByTourId($tour_id)
    {
        $sql = "SELECT * FROM tour_schedule
                WHERE tour_id = ?
                ORDER BY depart_date ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$tour_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ==========================
    // Tạo Schedule mới
    // ==========================
    public function create($data)
    {
        $sql = "INSERT INTO tour_schedule (tour_id, depart_date, return_date, meeting_point, seats_total, seats_booked, status)
                VALUES (:tour_id, :depart_date, :return_date, :meeting_point, :seats_total, :seats_booked, :status)";

        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute($data);
        
        // Return the inserted ID if successful
        if ($result) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }

    // ==========================
    // Cập nhật Schedule
    // ==========================
    public function update($id, $data)
    {
        $sql = "UPDATE tour_schedule
                SET tour_id = :tour_id, depart_date = :depart_date, return_date = :return_date, meeting_point = :meeting_point, 
                    seats_total = :seats_total, seats_booked = :seats_booked, status = :status
                WHERE schedule_id = :schedule_id";

        $data[':schedule_id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    // ==========================
    // Xóa Schedule
    // ==========================
    public function delete($id)
    {
        $sql = "DELETE FROM tour_schedule WHERE schedule_id = ?";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    // ==========================
    // Kiểm tra schedule trùng ngày
    // ==========================
    public function isDateConflict($tour_id, $depart_date, $ignoreId = null)
    {
        $sql = "SELECT COUNT(*) FROM tour_schedule
                WHERE tour_id = ?
                AND depart_date = ?";

        if ($ignoreId !== null) {
            $sql .= " AND schedule_id != " . intval($ignoreId);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$tour_id, $depart_date]);

        return $stmt->fetchColumn() > 0;
    }

    // ==========================
    // Update status
    // ==========================
    public function updateStatus($id, $status)
    {
        $sql = "UPDATE tour_schedule SET status = ? WHERE schedule_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$status, $id]);
    }
    
    // ==========================
    // Cập nhật số ghế
    // ==========================
    public function updateSeats($schedule_id, $seats_total)
    {
        $sql = "UPDATE tour_schedule SET seats_total = ? WHERE schedule_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$seats_total, $schedule_id]);
    }
    
    // ==========================
    // Lấy danh sách schedules với filter, search và pagination
    // ==========================
    public function getSchedulesWithFilters($tour_id = '', $status = '', $search = '', $page = 1, $itemsPerPage = 5)
    {
        $offset = ($page - 1) * $itemsPerPage;
        
        // Base query - Modified to include guide information
        $sql = "SELECT s.*, t.tour_name, t.tour_code, t.image, 
                       ga.guide_id as assigned_guide_id,
                       g.user_id as guide_user_id,
                       u.full_name as guide_name
                FROM tour_schedule s 
                JOIN tour t ON s.tour_id = t.tour_id 
                LEFT JOIN guide_assignment ga ON s.schedule_id = ga.schedule_id
                LEFT JOIN guide g ON ga.guide_id = g.guide_id
                LEFT JOIN users u ON g.user_id = u.user_id
                WHERE 1=1";
        $countSql = "SELECT COUNT(*) FROM tour_schedule s 
                     JOIN tour t ON s.tour_id = t.tour_id 
                     LEFT JOIN guide_assignment ga ON s.schedule_id = ga.schedule_id
                     LEFT JOIN guide g ON ga.guide_id = g.guide_id
                     LEFT JOIN users u ON g.user_id = u.user_id
                     WHERE 1=1";
        
        $params = [];
        
        // Tour filter
        if (!empty($tour_id)) {
            $sql .= " AND s.tour_id = :tour_id";
            $countSql .= " AND s.tour_id = :tour_id";
            $params[':tour_id'] = $tour_id;
        }
        
        // Status filter
        if (!empty($status)) {
            $sql .= " AND s.status = :status";
            $countSql .= " AND s.status = :status";
            $params[':status'] = $status;
        }
        
        // Search filter (search in tour name or meeting point)
        if (!empty($search)) {
            $sql .= " AND (t.tour_name LIKE :search OR s.meeting_point LIKE :search)";
            $countSql .= " AND (t.tour_name LIKE :search OR s.meeting_point LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }
        
        // Add ordering
        $sql .= " ORDER BY s.schedule_id DESC";
        
        // Add pagination
        $sql .= " LIMIT :limit OFFSET :offset";
        
        // Get total count
        $countStmt = $this->db->prepare($countSql);
        foreach ($params as $key => $value) {
            $countStmt->bindValue($key, $value);
        }
        $countStmt->execute();
        $total = $countStmt->fetchColumn();
        
        // Get paginated results
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'schedules' => $schedules,
            'total' => $total,
            'page' => $page,
            'totalPages' => ceil($total / $itemsPerPage)
        ];
    }
    
    // ==========================
    // Get schedules that are currently running (active tours)
    // ==========================
    public function getRunningSchedules()
    {
        $sql = "SELECT s.*, t.tour_name, t.tour_code, t.image,
                       ga.guide_id as assigned_guide_id,
                       g.user_id as guide_user_id,
                       u.full_name as guide_name
                FROM tour_schedule s
                JOIN tour t ON s.tour_id = t.tour_id
                LEFT JOIN guide_assignment ga ON s.schedule_id = ga.schedule_id
                LEFT JOIN guide g ON ga.guide_id = g.guide_id
                LEFT JOIN users u ON g.user_id = u.user_id
                WHERE s.status = 'open' AND s.depart_date <= CURDATE()
                ORDER BY s.depart_date DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // ==========================
    // Get schedule progress information
    // ==========================
    public function getScheduleProgress($schedule_id)
    {
        // Get total logs for this schedule
        $sql = "SELECT COUNT(*) as total_logs FROM daily_log WHERE schedule_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$schedule_id]);
        $totalLogs = $stmt->fetch(PDO::FETCH_ASSOC)['total_logs'];
        
        // Get schedule details
        $schedule = $this->getById($schedule_id);
        
        // Calculate progress based on tour duration and logs
        if ($schedule) {
            $start_date = new DateTime($schedule['depart_date']);
            // Use return_date if available, otherwise use depart_date
            $end_date = !empty($schedule['return_date']) ? new DateTime($schedule['return_date']) : new DateTime($schedule['depart_date']);
            $interval = $start_date->diff($end_date);
            $tour_duration = $interval->days > 0 ? $interval->days : 1; // Avoid division by zero
            
            // Progress is based on logs submitted vs expected duration
            $progress = min(100, ($totalLogs / $tour_duration) * 100);
            
            return [
                'schedule' => $schedule,
                'total_logs' => $totalLogs,
                'progress' => round($progress, 2)
            ];
        }
        
        return null;
    }
    
    // ==========================
    // Mark schedule logs as approved (confirm tour completion)
    // ==========================
    public function approveLogs($schedule_id)
    {
        $sql = "UPDATE tour_schedule SET logs_approved = 1, status = 'completed' WHERE schedule_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$schedule_id]);
    }
    
    // ==========================
    // Check if logs are approved
    // ==========================
    public function areLogsApproved($schedule_id)
    {
        $sql = "SELECT logs_approved FROM tour_schedule WHERE schedule_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$schedule_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['logs_approved'] == 1 : false;
    }
}