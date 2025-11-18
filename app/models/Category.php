<?php
class Category extends BaseModel
{
    public function all()
    {
        $stmt = $this->db->query('SELECT * FROM category ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }
}
