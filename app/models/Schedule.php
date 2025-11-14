<?php
class Schedule extends BaseModel {
    public function all(){
        $stmt = $this->db->query('SELECT s.*, t.tour_name FROM tour_schedule s JOIN tour t ON s.tour_id=t.tour_id ORDER BY s.depart_date DESC');
        return $stmt->fetchAll();
    }
    public function find($id){
        $stmt = $this->db->prepare('SELECT * FROM tour_schedule WHERE schedule_id = :id');
        $stmt->execute([':id'=>$id]); return $stmt->fetch();
    }
    public function create($data){
        $stmt = $this->db->prepare('INSERT INTO tour_schedule (tour_id,depart_date,meeting_point,seats_total,seats_booked,status) VALUES(:tour_id,:depart_date,:meeting_point,:seats_total,0,:status)');
        return $stmt->execute($data);
    }
    public function update($id,$data){
        $data[':id']=$id;
        $stmt = $this->db->prepare('UPDATE tour_schedule SET tour_id=:tour_id, depart_date=:depart_date, meeting_point=:meeting_point, seats_total=:seats_total, status=:status WHERE schedule_id=:id');
        return $stmt->execute($data);
    }
}
?>