<?php
class Tour extends BaseModel
{
    public function getDb()
    {
        return $this->db;
    }

    public function getAllTour()
    {
        $stmt = $this->db->prepare("SELECT * FROM tour ORDER BY tour_id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function getToursWithFilters($search = '', $min_price = '', $max_price = '', $status = '', $page = 1, $itemsPerPage = 5)
{
    $page = is_numeric($page) ? (int)$page : 1;
    $itemsPerPage = is_numeric($itemsPerPage) ? (int)$itemsPerPage : 5;
    $offset = ($page - 1) * $itemsPerPage;

    // Truy vấn chính lấy tour (có cột image)
    $sql = "SELECT * FROM tour WHERE 1=1";
    $countSql = "SELECT COUNT(*) FROM tour WHERE 1=1";

    $params = [];

    // Search filter
    if (!empty($search)) {
        $sql .= " AND (tour_name LIKE :search OR tour_code LIKE :search)";
        $countSql .= " AND (tour_name LIKE :search OR tour_code LIKE :search)";
        $params[':search'] = "%$search%";
    }

    // Price filter
    if (!empty($min_price)) {
        $sql .= " AND price >= :min_price";
        $countSql .= " AND price >= :min_price";
        $params[':min_price'] = $min_price;
    }

    if (!empty($max_price)) {
        $sql .= " AND price <= :max_price";
        $countSql .= " AND price <= :max_price";
        $params[':max_price'] = $max_price;
    }

    // Status filter
    if (!empty($status)) {
        $sql .= " AND status = :status";
        $countSql .= " AND status = :status";
        $params[':status'] = $status;
    }

    // Order và phân trang
    $sql .= " ORDER BY tour_id DESC LIMIT :limit OFFSET :offset";

    // Query đếm tổng số
    $countStmt = $this->db->prepare($countSql);
    foreach ($params as $key => $value) {
        $countStmt->bindValue($key, $value);
    }
    $countStmt->execute();
    $total = $countStmt->fetchColumn();

    // Query chính
    $stmt = $this->db->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $tours = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return [
        'tours' => $tours,
        'total' => $total
    ];
}



     public function getToursById($id) {
        $stmt = $this->db->prepare("SELECT * FROM tour WHERE tour_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getDetailTour($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM tour WHERE tour_id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function generateTourCode()
    {
        $stmt = $this->db->prepare("SELECT tour_id FROM tour ORDER BY tour_id DESC LIMIT 1");
        $stmt->execute();
        $lastTour = $stmt->fetch(PDO::FETCH_ASSOC);

        $nextId = $lastTour ? $lastTour['tour_id'] + 1 : 1;

        $year = date('Y');
        $code = 'TOUR' . $year . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        return $code;
    }

    public function insertTour($name, $code, $price, $duration_days, $description, $status, $image)
    {
        $sql = "INSERT INTO tour (tour_name, tour_code, price, duration_days, description, status, image)
                VALUES (:name, :code, :price, :duration_days, :description, :status, :image)";

        $stm = $this->db->prepare($sql);
        $stm->execute([
            ':name' => $name,
            ':code' => $code,
            ':price' => $price,
            ':duration_days' => $duration_days,
            ':description' => $description,
            ':status' => $status,
            ':image' => $image
        ]);

        return $this->db->lastInsertId();
    }

    public function updateTour($id, $ten, $gia, $duration_days, $mo_ta, $trang_thai, $image = null)
{
    // Câu lệnh SQL cơ bản
    $sql = "UPDATE tour 
            SET tour_name = :tour_name,
                price = :price,
                duration_days = :duration_days,
                description = :description,
                status = :status";

    // Nếu có ảnh mới thì cập nhật, nếu không thì giữ nguyên
    if ($image !== null) {
        $sql .= ", image = :image";
    }

    $sql .= " WHERE tour_id = :tour_id";

    $stmt = $this->db->prepare($sql);

    // Gán giá trị
    $params = [
        ':tour_name'   => $ten,
        ':price'       => $gia,
        ':duration_days' => $duration_days,
        ':description' => $mo_ta,
        ':status'      => $trang_thai,
        ':tour_id'     => $id
    ];

    if ($image !== null) {
        $params[':image'] = $image;
    }

    return $stmt->execute($params);
}


   public function deleteTour($id)
{
    try {
        $this->db->beginTransaction();

        // 1. Lấy danh sách schedule_id của tour
        $scheduleStmt = $this->db->prepare("SELECT schedule_id FROM tour_schedule WHERE tour_id = :tour_id");
        $scheduleStmt->execute([':tour_id' => $id]);
        $schedules = $scheduleStmt->fetchAll(PDO::FETCH_COLUMN);

        if (!empty($schedules)) {
            $inClause = implode(',', array_fill(0, count($schedules), '?'));

            // 2. Xóa guide_assignment liên quan
            $guideStmt = $this->db->prepare("DELETE FROM guide_assignment WHERE schedule_id IN ($inClause)");
            $guideStmt->execute($schedules);

            // 3. Lấy danh sách customer_id từ booking
            $customerStmt = $this->db->prepare("SELECT DISTINCT customer_id FROM booking WHERE schedule_id IN ($inClause)");
            $customerStmt->execute($schedules);
            $customers = $customerStmt->fetchAll(PDO::FETCH_COLUMN);

            // 4. Xóa booking liên quan
            $bookingStmt = $this->db->prepare("DELETE FROM booking WHERE schedule_id IN ($inClause)");
            $bookingStmt->execute($schedules);

            // 5. Xóa booking_customer nếu không còn booking nào tham chiếu
            if (!empty($customers)) {
                $customerInClause = implode(',', array_fill(0, count($customers), '?'));
                $checkStmt = $this->db->prepare("SELECT customer_id FROM booking WHERE customer_id IN ($customerInClause)");
                $checkStmt->execute($customers);
                $stillUsed = $checkStmt->fetchAll(PDO::FETCH_COLUMN);

                $toDelete = array_diff($customers, $stillUsed);
                if (!empty($toDelete)) {
                    $deleteCustomerStmt = $this->db->prepare("DELETE FROM booking_customer WHERE customer_id IN (" . implode(',', array_fill(0, count($toDelete), '?')) . ")");
                    $deleteCustomerStmt->execute($toDelete);
                }
            }
        }

        // 6. Xóa tour_schedule
        $scheduleDeleteStmt = $this->db->prepare("DELETE FROM tour_schedule WHERE tour_id = :tour_id");
        $scheduleDeleteStmt->execute([':tour_id' => $id]);

        // 7. Xóa tour_image
        $imageStmt = $this->db->prepare("DELETE FROM tour_image WHERE tour_id = :tour_id");
        $imageStmt->execute([':tour_id' => $id]);

        // 8. Xóa tour
        $tourStmt = $this->db->prepare("DELETE FROM tour WHERE tour_id = :tour_id");
        $tourStmt->execute([':tour_id' => $id]);

        $this->db->commit();
    } catch (Exception $e) {
        $this->db->rollBack();
        throw $e;
    }
}







    public function getTourImages($tour_id)
    {
        $sql = "SELECT * FROM tour_image WHERE tour_id = :tour_id ORDER BY is_primary DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':tour_id' => $tour_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addTourImage($tour_id, $image_path, $is_primary = 0)
    {
        $sql = "INSERT INTO tour_image (tour_id, image_path, is_primary) 
                VALUES (:tour_id, :image_path, :is_primary)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':tour_id' => $tour_id,
            ':image_path' => $image_path,
            ':is_primary' => $is_primary
        ]);
    }

    public function deleteTourImage($image_id)
    {
        $sql = "DELETE FROM tour_image WHERE image_id = :image_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':image_id' => $image_id]);
    }

    public function setPrimaryImage($tour_id, $image_id)
    {
        $sql1 = "UPDATE tour_image SET is_primary = 0 WHERE tour_id = :tour_id";
        $stmt1 = $this->db->prepare($sql1);
        $stmt1->execute([':tour_id' => $tour_id]);

        $sql2 = "UPDATE tour_image SET is_primary = 1 WHERE image_id = :image_id";
        $stmt2 = $this->db->prepare($sql2);
        return $stmt2->execute([':image_id' => $image_id]);
    }

    public function validateTourDates($start_date, $created_at)
    {
        $start = new DateTime($start_date);
        $created = new DateTime($created_at);
        return $start >= $created;
    }

    public function getStatusOptions()
    {
        return [
            'draft' => 'Bản nháp',
            'pending_approval' => 'Chờ phê duyệt',
            'active' => 'Hoạt động',
            'inactive' => 'Ngừng hoạt động',
            'suspended' => 'Tạm ngưng',
            'cancelled' => 'Đã hủy',
            'completed' => 'Đã hoàn thành'
        ];
    }

    public function getStatusBadgeClass($status)
    {
        $badgeClasses = [
            'draft' => 'bg-yellow-100 text-yellow-800',
            'pending_approval' => 'bg-blue-100 text-blue-800',
            'active' => 'bg-green-100 text-green-800',
            'inactive' => 'bg-gray-100 text-gray-800',
            'suspended' => 'bg-orange-100 text-orange-800',
            'cancelled' => 'bg-red-100 text-red-800',
            'completed' => 'bg-purple-100 text-purple-800'
        ];

        return $badgeClasses[$status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getStatusDescription($status)
    {
        $descriptions = [
            'draft' => 'Tour đang trong quá trình xây dựng, chưa sẵn sàng để công bố',
            'pending_approval' => 'Tour đang chờ quản lý phê duyệt trước khi công bố',
            'active' => 'Tour đang hoạt động và sẵn sàng để đặt chỗ',
            'inactive' => 'Tour tạm thời không khả dụng để đặt chỗ',
            'suspended' => 'Tour bị tạm ngưng do lý do kỹ thuật hoặc vận hành',
            'cancelled' => 'Tour đã bị hủy và không còn khả dụng',
            'completed' => 'Tour đã kết thúc và không còn mở đặt chỗ'
        ];

        return $descriptions[$status] ?? 'Trạng thái không xác định';
    }

    // Update tour status
    public function updateTourStatus($tour_id, $new_status)
    {
        $validStatuses = array_keys($this->getStatusOptions());
        if (!in_array($new_status, $validStatuses)) {
            throw new InvalidArgumentException("Invalid status: $new_status");
        }

        $tour = $this->getDetailTour($tour_id);
        if (!$tour) {
            throw new InvalidArgumentException("Tour not found: $tour_id");
        }

        $current_status = $tour['status'];
        if ($current_status == 'cancelled' && $new_status != 'draft') {
            throw new InvalidArgumentException("Cannot change status from cancelled to $new_status");
        }

        if ($current_status == 'completed' && $new_status != 'active') {
            throw new InvalidArgumentException("Cannot change status from completed to $new_status");
        }

        $sql = "UPDATE tour SET status = :status WHERE tour_id = :tour_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':status' => $new_status,
            ':tour_id' => $tour_id
        ]);
    }

    // Get tours by status
    public function getToursByStatus($status, $limit = null)
    {
        $sql = "SELECT * FROM tour 
                WHERE status = :status 
                ORDER BY created_at DESC";

        if ($limit) {
            $sql .= " LIMIT :limit";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':status', $status);

        if ($limit) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Check if tour is available for booking
    public function isTourAvailableForBooking($tour_id)
    {
        $tour = $this->getDetailTour($tour_id);
        if (!$tour) {
            return false;
        }

        $current_date = new DateTime();
        $start_date = new DateTime($tour['start_date']);

        return $tour['status'] == 'active' && $start_date >= $current_date;
    }
}
