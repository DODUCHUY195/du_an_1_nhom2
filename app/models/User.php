<?php
class User extends BaseModel
{
    public function findByEmail($email)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :e LIMIT 1');
        $stmt->execute([':e' => $email]);
        return $stmt->fetch();
    }
    public function create($data)
    {
        $stmt = $this->db->prepare('INSERT INTO users (full_name,email,phone,password,role,activated) VALUES(:n,:e,:p,:pw,:r,:a)');
        return $stmt->execute($data);
    }
}
