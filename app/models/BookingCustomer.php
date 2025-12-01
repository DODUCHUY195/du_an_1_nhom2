<?php
class BookingCustomer extends BaseModel{
   
    

    public function getCustomersByBooking($booking_id) {
        $stmt = $this->db->prepare("SELECT * FROM booking_customer WHERE booking_id = ?");
        $stmt->execute([$booking_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Thêm khách mới
    public function addCustomer($data) {
        $stmt = $this->db->prepare("
            INSERT INTO booking_customer (booking_id, full_name, gender, birth_year, id_number, special_request)
            VALUES (:booking_id, :full_name, :gender, :birth_year, :id_number, :special_request)
        ");
        return $stmt->execute($data);
    }

    // 3. Cập nhật yêu cầu đặc biệt
    public function updateSpecialRequest($customer_id, $special_request) {
        $stmt = $this->db->prepare("UPDATE booking_customer SET special_request = ? WHERE customer_id = ?");
        return $stmt->execute([$special_request, $customer_id]);
    }

    // 4. Đánh dấu điểm danh
    public function checkInCustomer($customer_id) {
        $stmt = $this->db->prepare("UPDATE booking_customer SET checked_in = 1 WHERE customer_id = ?");
        return $stmt->execute([$customer_id]);
    }

     public function getAllBookings() {
        $stmt = $this->db->query("SELECT booking_id, booking_code, booking_date, payment_method FROM booking ORDER BY booking_id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin khách theo ID
public function getCustomerById($customer_id) {
    $stmt = $this->db->prepare("SELECT * FROM booking_customer WHERE customer_id  = ?");
    $stmt->execute([$customer_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Cập nhật thông tin khách
public function updateCustomer($customer_id, $full_name, $gender, $birth_year, $id_number, $special_request) {
    $stmt = $this->db->prepare("
        UPDATE booking_customer 
        SET full_name = ?, gender = ?, birth_year = ?, id_number = ?, special_request = ? 
        WHERE customer_id = ?
    ");
    return $stmt->execute([$full_name, $gender, $birth_year, $id_number, $special_request, $customer_id]);
}

//     // Lấy thông tin khách theo ID
// public function getCustomerById($customer_id) {
//     $stmt = $this->db->prepare("SELECT * FROM booking_customer WHERE customer_id = ?");
//     $stmt->execute([$customer_id]);
//     return $stmt->fetch(PDO::FETCH_ASSOC);
// }

// // Cập nhật thông tin khách
// public function updateCustomer($customer_id, $data) {
//     $stmt = $this->db->prepare("
//         UPDATE booking_customer 
//         SET full_name = :full_name, gender = :gender, birth_year = :birth_year, 
//             id_number = :id_number, special_request = :special_request
//         WHERE customer_id = :customer_id
//     ");
//     $data['customer_id'] = $customer_id;
//     return $stmt->execute($data);
// }
}
?>
