<?php
class Guide extends BaseModel {
    public function all(){
        $stmt = $this->db->query('SELECT g.*, u.full_name AS user_name FROM guide g LEFT JOIN users u ON g.user_id = u.user_id ORDER BY g.created_at DESC');
        return $stmt->fetchAll();
    }
    public function find($id){
        $stmt = $this->db->prepare('SELECT * FROM guide WHERE guide_id = :id');
        $stmt->execute([':id'=>$id]); return $stmt->fetch();
    }
    public function create($data){
        $stmt = $this->db->prepare('INSERT INTO guide (user_id,license_no,note) VALUES(:user_id,:license_no,:note)');
        return $stmt->execute($data);
    }
    public function update($id,$data){
        $data[':id']=$id;
        $stmt = $this->db->prepare('UPDATE guide SET user_id=:user_id, license_no=:license_no, note=:note WHERE guide_id=:id');
        return $stmt->execute($data);
    }
}
?>