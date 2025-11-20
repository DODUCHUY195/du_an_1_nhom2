<?php
class Category extends BaseModel
{
    // Lấy tất cả danh mục (ưu tiên dùng hàm của bạn)
    public function all()
    {
        $sql = "SELECT * FROM category ORDER BY category_id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy tất cả danh mục (hàm bên main)
    public function getAllDanhMuc()
    {
        $sql = "SELECT * FROM category ORDER BY category_id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tìm theo ID
    public function find($id)
    {
        $sql = "SELECT * FROM category WHERE category_id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Hàm bên main: lấy 1 danh mục
    public function getById($id)
    {
        return $this->find($id);
    }

    // Xóa danh mục (hàm bạn làm)
    public function delete($id)
    {
        $sql = "DELETE FROM category WHERE category_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Xóa danh mục (hàm bên main)
    public function deleteDanhMuc($id)
    {
        return $this->delete($id);
    }

    // Hàm thêm danh mục (bên main)
    public function insertDanhMuc($ten_danh_muc, $mo_ta)
    {
        try {
            $sql = "INSERT INTO category (category_name, description)
                    VALUES (:category_name, :description)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':category_name' => $ten_danh_muc,
                ':description' => $mo_ta
            ]);
            return true;
        } catch (Exception $e) {
            echo 'Lỗi: ' . $e->getMessage();
            return false;
        }
    }

    // Hàm thêm kiểu BaseModel (dự phòng)
    public function insert($data = [])
    {
        return $this->insertDanhMuc($data['category_name'] ?? '', $data['description'] ?? '');
    }
}
