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
            $sql = 'INSERT INTO  danh_mucs (ten_danh_muc,mo_ta)
                    VALUES (:ten_danh_muc,:mo_ta)
            ';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':ten_danh_muc' => $ten_danh_muc,
                ':mo_ta' => $mo_ta
            ]);
            return true;
        } catch (Exception $e) {
            echo 'loi' . $e->getMessage();
        }
    }

}
