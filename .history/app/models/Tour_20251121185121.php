<?php
class Tour extends BaseModel
{
   public function getAllTour()
    {
        try {
            $sql = 'SELECT * FROM tour';
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo 'loi' . $e->getMessage();
        }
    }
    

    public function getDetailTour($id)
    {
        try {
            $sql = 'SELECT * FROM tour WHERE tour_id= :tour_id';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':tour_id' => $id

            ]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'loi' . $e->getMessage();
        }
    }

     public function updateTour($id, $ten, $gia, $duration, $mo_ta, $trang_thai, $thoi_gian_tao)
    {
        try {
            $sql = 'UPDATE  tour SET tour_name = :tour_name,price = :price,duration_days = :duration_days,description = :description,status = :status,created_at = :created_at WHERE, tour_id = :tour_id';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':tour_name' => $ten,
                ':price' => $gia,
                ':duration_days' => $duration,
                ':description' => $mo_ta,
                ':status' => $trang_thai,
                ':created_at' => $thoi_gian_tao,

            ]);
            return true;
        } catch (Exception $e) {
            echo 'loi' . $e->getMessage();
        }
    }
}
