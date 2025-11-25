<?php

class User extends BaseModel
{
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    public function create(array $data): bool
    {
        $sql = 'INSERT INTO users (full_name, email, phone, password, role, activated)
                VALUES (:full_name, :email, :phone, :password, :role, :activated)';

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':full_name' => $data['full_name'],
            ':email'     => $data['email'],
            ':phone'     => $data['phone'] ?? null,
            ':password'  => $data['password'],
            ':role'      => $data['role'] ?? 'customer',
            ':activated' => $data['activated'] ?? 0,
        ]);
    }
}
