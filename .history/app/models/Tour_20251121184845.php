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
}
