<?php
class Tour extends BaseModel
{
    public function getAllTour()
    {
        $stmt = $this->db->prepare("SELECT * FROM tour");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDetailTour($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM tour WHERE tour_id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertTour($name, $code, $price, $description, $days, $status)
    {
        $sql = "INSERT INTO tour (tour_name, tour_code, price, description, duration_days, status)
                VALUES (:name, :code, :price, :description, :days, :status)";

        $stm = $this->db->prepare($sql);
        $stm->execute([
            ':name' => $name,
            ':code' => $code, // NULL tạm thời nếu không nhập
            ':price' => $price,
            ':description' => $description,
            ':days' => $days,
            ':status' => $status
        ]);
    }

    public function updateTour($id, $ten, $gia, $duration, $mo_ta, $trang_thai, $thoi_gian_tao)
    {
        $sql = "UPDATE tour 
                SET tour_name = :tour_name,
                    price = :price,
                    duration_days = :duration_days,
                    description = :description,
                    status = :status,
                    created_at = :created_at
                WHERE tour_id = :tour_id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':tour_name' => $ten,
            ':price' => $gia,
            ':duration_days' => $duration,
            ':description' => $mo_ta,
            ':status' => $trang_thai,
            ':created_at' => $thoi_gian_tao,
            ':tour_id' => $id
        ]);
    }
}
