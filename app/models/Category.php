<?php
class Category extends BaseModel
{
    public function getAllDanhMuc()
    {
        try {
            $sql = 'SELECT * FROM category';
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo 'loi' . $e->getMessage();
        }
    }

     public function getDetailDanhMuc($id)
    {
        try {
            $sql = 'SELECT * FROM category WHERE category_id= :category_id';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':category_id' => $id

            ]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'loi' . $e->getMessage();
        }
    }

    public function insertDanhMuc($ten_danh_muc, $mo_ta)
    {
        try {
            $sql = 'INSERT INTO  category (category_name,description)
                    VALUES (:category_name,:description)
            ';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':category_name' => $ten_danh_muc,
                ':description' => $mo_ta
            ]);
            return true;
        } catch (Exception $e) {
            echo 'loi' . $e->getMessage();
        }
    }

    public function updateDanhMuc($id, $ten_danh_muc, $mo_ta)
    {
        try {
            $sql = 'UPDATE  category SET category_name = :category_name,description = :description WHERE category_id = :category_id';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':category_id' => $id,
                ':category_name' => $ten_danh_muc,
                ':description' => $mo_ta,

            ]);
            return true;
        } catch (Exception $e) {
            echo 'loi' . $e->getMessage();
        }
    }

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

}
