<?php
class Tour extends BaseModel
{
    // Add a getter method for the database connection
    public function getDb() {
        return $this->db;
    }

    public function getAllTour()
    {
        $stmt = $this->db->prepare("SELECT t.*, c.category_name FROM tour t LEFT JOIN category c ON t.category_id = c.category_id ORDER BY t.tour_id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // New method to get tours with filters and pagination
    public function getToursWithFilters($category_id = '', $search = '', $min_price = '', $max_price = '', $status = '', $page = 1, $itemsPerPage = 5)
    {
        $offset = ($page - 1) * $itemsPerPage;
        
        // Base query
        $sql = "SELECT t.*, c.category_name FROM tour t LEFT JOIN category c ON t.category_id = c.category_id WHERE 1=1";
        $countSql = "SELECT COUNT(*) FROM tour t LEFT JOIN category c ON t.category_id = c.category_id WHERE 1=1";
        
        $params = [];
        
        // Category filter
        if (!empty($category_id)) {
            $sql .= " AND t.category_id = :category_id";
            $countSql .= " AND t.category_id = :category_id";
            $params[':category_id'] = $category_id;
        }
        
        // Search filter
        if (!empty($search)) {
            $sql .= " AND (t.tour_name LIKE :search OR t.tour_code LIKE :search)";
            $countSql .= " AND (t.tour_name LIKE :search OR t.tour_code LIKE :search)";
            $params[':search'] = "%$search%";
        }
        
        // Price filter
        if (!empty($min_price)) {
            $sql .= " AND t.price >= :min_price";
            $countSql .= " AND t.price >= :min_price";
            $params[':min_price'] = $min_price;
        }
        
        if (!empty($max_price)) {
            $sql .= " AND t.price <= :max_price";
            $countSql .= " AND t.price <= :max_price";
            $params[':max_price'] = $max_price;
        }
        
        // Status filter
        if (!empty($status)) {
            $sql .= " AND t.status = :status";
            $countSql .= " AND t.status = :status";
            $params[':status'] = $status;
        }
        
        // Order and limit
        $sql .= " ORDER BY t.tour_id DESC LIMIT :limit OFFSET :offset";
        
        // Prepare and execute count query
        $countStmt = $this->db->prepare($countSql);
        foreach ($params as $key => $value) {
            $countStmt->bindValue($key, $value);
        }
        $countStmt->execute();
        $total = $countStmt->fetchColumn();
        
        // Prepare and execute main query
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

    public function getDetailTour($id)
    {
        $stmt = $this->db->prepare("SELECT t.*, c.category_name FROM tour t LEFT JOIN category c ON t.category_id = c.category_id WHERE t.tour_id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tạo mã tour tự động
    public function generateTourCode() {
        // Lấy tour mới nhất để tạo mã tiếp theo
        $stmt = $this->db->prepare("SELECT tour_id FROM tour ORDER BY tour_id DESC LIMIT 1");
        $stmt->execute();
        $lastTour = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Nếu chưa có tour nào, bắt đầu từ 1
        $nextId = $lastTour ? $lastTour['tour_id'] + 1 : 1;
        
        // Tạo mã tour theo định dạng: TOUR + năm + 4 chữ số
        $year = date('Y');
        $code = 'TOUR' . $year . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        
        return $code;
    }

    public function insertTour($name, $code, $price, $duration_days, $description, $status, $category_id = null, $image = null)
    {
        $sql = "INSERT INTO tour (tour_name, tour_code, price, duration_days, description, status, category_id, image)
                VALUES (:name, :code, :price, :duration_days, :description, :status, :category_id, :image)";

        $stm = $this->db->prepare($sql);
        $stm->execute([
            ':name' => $name,
            ':code' => $code,
            ':price' => $price,
            ':duration_days' => $duration_days,
            ':description' => $description,
            ':status' => $status,
            ':category_id' => $category_id,
            ':image' => $image
        ]);
        
        return $this->db->lastInsertId();
    }

    public function updateTour($id, $ten, $gia, $duration_days, $mo_ta, $trang_thai, $category_id, $image = null)
    {
        // Debug: Log the parameters
        error_log("updateTour called with ID: " . $id);
        error_log("Parameters: " . json_encode(func_get_args()));
        
        // Xây dựng câu lệnh SQL động tùy theo có ảnh mới hay không
        $sql = "UPDATE tour 
                SET tour_name = :tour_name,
                    price = :price,
                    duration_days = :duration_days,
                    description = :description,
                    status = :status,
                    category_id = :category_id";
        
        // Chỉ thêm image vào câu lệnh nếu có giá trị
        if ($image !== null) {
            $sql .= ", image = :image";
        }
        
        $sql .= " WHERE tour_id = :tour_id";

        $stmt = $this->db->prepare($sql);
        
        // Chuẩn bị mảng tham số
        $params = [
            ':tour_name' => $ten,
            ':price' => $gia,
            ':duration_days' => $duration_days,
            ':description' => $mo_ta,
            ':status' => $trang_thai,
            ':category_id' => $category_id,
            ':tour_id' => $id
        ];
        
        // Chỉ thêm image vào mảng tham số nếu có giá trị
        if ($image !== null) {
            $params[':image'] = $image;
        }
        
        $result = $stmt->execute($params);
        
        // Debug: Log the result
        error_log("updateTour result: " . ($result ? "success" : "failed"));
        error_log("Affected rows: " . $stmt->rowCount());
        
        return $result;
    }
    
    
    // Xóa hẳn tour
    public function deleteTour($id)
    {
        $sql = "DELETE FROM tour WHERE tour_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
    
    // Lấy hình ảnh cho tour
    public function getTourImages($tour_id) {
        $sql = "SELECT * FROM tour_image WHERE tour_id = :tour_id ORDER BY is_primary DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':tour_id' => $tour_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Thêm hình ảnh cho tour
    public function addTourImage($tour_id, $image_path, $is_primary = 0) {
        $sql = "INSERT INTO tour_image (tour_id, image_path, is_primary) 
                VALUES (:tour_id, :image_path, :is_primary)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':tour_id' => $tour_id,
            ':image_path' => $image_path,
            ':is_primary' => $is_primary
        ]);
    }
    
    // Xóa hình ảnh
    public function deleteTourImage($image_id) {
        $sql = "DELETE FROM tour_image WHERE image_id = :image_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':image_id' => $image_id]);
    }
    
    // Đặt hình ảnh chính
    public function setPrimaryImage($tour_id, $image_id) {
        // Bỏ đánh dấu hình ảnh chính cũ
        $sql1 = "UPDATE tour_image SET is_primary = 0 WHERE tour_id = :tour_id";
        $stmt1 = $this->db->prepare($sql1);
        $stmt1->execute([':tour_id' => $tour_id]);
        
        // Đánh dấu hình ảnh mới là chính
        $sql2 = "UPDATE tour_image SET is_primary = 1 WHERE image_id = :image_id";
        $stmt2 = $this->db->prepare($sql2);
        return $stmt2->execute([':image_id' => $image_id]);
    }
    
    // Lấy tất cả tour theo category
    public function getToursByCategory($category_id) {
        $sql = "SELECT t.*, c.category_name FROM tour t LEFT JOIN category c ON t.category_id = c.category_id WHERE t.category_id = :category_id ORDER BY t.tour_id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':category_id' => $category_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Kiểm tra ngày bắt đầu không nhỏ hơn ngày tạo
    public function validateTourDates($start_date, $created_at) {
        $start = new DateTime($start_date);
        $created = new DateTime($created_at);
        return $start >= $created;
    }
    
    // Professional status management methods
    public function getStatusOptions() {
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
    
    public function getStatusBadgeClass($status) {
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
    
    public function getStatusDescription($status) {
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
    public function updateTourStatus($tour_id, $new_status) {
        // Validate status
        $validStatuses = array_keys($this->getStatusOptions());
        if (!in_array($new_status, $validStatuses)) {
            throw new InvalidArgumentException("Invalid status: $new_status");
        }
        
        // Check if tour exists
        $tour = $this->getDetailTour($tour_id);
        if (!$tour) {
            throw new InvalidArgumentException("Tour not found: $tour_id");
        }
        
        // Prevent certain status transitions
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
    public function getToursByStatus($status, $limit = null) {
        $sql = "SELECT t.*, c.category_name FROM tour t 
                LEFT JOIN category c ON t.category_id = c.category_id 
                WHERE t.status = :status 
                ORDER BY t.created_at DESC";
        
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
    public function isTourAvailableForBooking($tour_id) {
        $tour = $this->getDetailTour($tour_id);
        if (!$tour) {
            return false;
        }
        
        // Tour is available if it's active and start date is in the future
        $current_date = new DateTime();
        $start_date = new DateTime($tour['start_date']);
        
        return $tour['status'] == 'active' && $start_date >= $current_date;
    }
}