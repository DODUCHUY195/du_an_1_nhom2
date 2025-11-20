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
            $sql = 'SELECT * FROM danh_mucs WHERE id= :id';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':id' => $id

            ]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'loi' . $e->getMessage();
        }
    }
}
