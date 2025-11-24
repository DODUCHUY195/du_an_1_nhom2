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
    public function find($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM tour WHERE tour_id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    public function insert($data)
    {
        $stmt = $this->db->prepare('INSERT INTO tour (category_id,tour_code,tour_name,price,duration_days,description,status) VALUES(:category_id,:tour_code,:tour_name,:price,:duration_days,:description,:status)');
        return $stmt->execute($data);
    }
    public function update($id, $data)
    {
        $data[':id'] = $id;
        $stmt = $this->db->prepare('UPDATE tour SET category_id=:category_id,tour_code=:tour_code,tour_name=:tour_name,price=:price,duration_days=:duration_days,description=:description,status=:status WHERE tour_id=:id');
        return $stmt->execute($data);
    }
}
