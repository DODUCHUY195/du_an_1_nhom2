<?php
class Category extends BaseModel
{
    // Lấy tất cả danh mục (phiên bản cũ/new)
    public function all()
    {
        $sql = "SELECT * FROM category ORDER BY category_id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAllDanhMuc()
    {
        $sql = "SELECT * FROM category ORDER BY category_id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy chi tiết
    public function find($id)
    {
        $sql = "SELECT * FROM category WHERE category_id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getDetailDanhMuc($id)
    {
        return $this->find($id);
    }

    // Thêm
    public function insertDanhMuc($ten_danh_muc, $mo_ta)
    {
        try {
            $sql = "INSERT INTO category (category_name, description) VALUES (:category_name, :description)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':category_name' => $ten_danh_muc,
                ':description' => $mo_ta
            ]);
            return true;
        } catch (Exception $e) {
            // để debug tạm thời
            echo 'Lỗi: ' . $e->getMessage();
            return false;
        }
    }

    // Update
    public function updateDanhMuc($id, $ten_danh_muc, $mo_ta)
    {
        try {
            $sql = "UPDATE category SET category_name = :category_name, description = :description WHERE category_id = :category_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':category_id' => $id,
                ':category_name' => $ten_danh_muc,
                ':description' => $mo_ta
            ]);
            return true;
        } catch (Exception $e) {
            echo 'Lỗi: ' . $e->getMessage();
            return false;
        }
    }

    // Xóa
    public function delete($id)
    {
        $sql = "DELETE FROM category WHERE category_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function deleteDanhMuc($id)
    {
        return $this->delete($id);
    }

    // Backup insert để tương thích
    public function insert($data = [])
    {
        return $this->insertDanhMuc($data['category_name'] ?? '', $data['description'] ?? '');
    }

    // Backup update generic (nếu BaseModel hỗ trợ)
    public function update($id, $data = [])
    {
        return $this->updateDanhMuc($id, $data['category_name'] ?? '', $data['description'] ?? '');
    }
}
