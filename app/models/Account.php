<?php
class Account extends BaseModel
{

    // Danh sách khách hàng
    public function getCustomers($keyword = "")
    {
        $sql = "SELECT * FROM account WHERE role = 'Customer'";
        if ($keyword != "") {
            $sql .= " AND (full_name LIKE :kw OR email LIKE :kw)";
        }
        $stmt = $this->db->prepare($sql);
        if ($keyword != "") {
            $stmt->bindValue(":kw", "%$keyword%");
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM account WHERE account_id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function search($keyword = '')
    {
        if ($keyword === '') {
            $stmt = $this->db->query("SELECT * FROM account ORDER BY created_at DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        $stmt = $this->db->prepare("
            SELECT * FROM account
            WHERE full_name LIKE :kw OR email LIKE :kw
            ORDER BY created_at DESC
        ");
        $kw = '%' . $keyword . '%';
        $stmt->execute([':kw' => $kw]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM account");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function find($account_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM account WHERE account_id = :id");
        $stmt->execute([':id' => $account_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM account WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO account (full_name, email, phone, password, activated, role)
            VALUES (:full_name, :email, :phone, :password, :activated, :role)
        ");
        $password = !empty($data['password']) ? password_hash($data['password'], PASSWORD_BCRYPT) : null;
        return $stmt->execute([
            ':full_name' => $data['full_name'],
            ':email'     => $data['email'],
            ':phone'     => $data['phone'],
            ':password'  => $password,
            ':activated' => $data['activated'] ?? 0,
            ':role'      => $data['role'] ?? 'Customer',
        ]);
    }

    //     public function update($id, $data) {
    //     $fields = [];
    //     foreach ($data as $key => $value) {
    //         $fields[] = "$key = :$key";
    //     }
    //     $sql = "UPDATE account SET " . implode(', ', $fields) . " WHERE account_id = :id";
    //     $stmt = $this->db->prepare($sql);
    //     $data['id'] = $id;
    //     $stmt->execute($data);
    // }

    public function resetPassword($account_id, $newPassword)
{
    $stmt = $this->db->prepare("
        UPDATE account
        SET password = :password
        WHERE account_id = :id
    ");
    $hashed = password_hash($newPassword, PASSWORD_BCRYPT);
    return $stmt->execute([
        ':password' => $hashed,
        ':id'       => $account_id,
    ]);
}

    public function update($account_id, $data)
    {
        if (!empty($data['password'])) {
            $stmt = $this->db->prepare("
            UPDATE account
            SET full_name = :full_name,
                email = :email,
                phone = :phone,
                password = :password,
                activated = :activated,
                role = :role
            WHERE account_id = :id
        ");
            $password = password_hash($data['password'], PASSWORD_BCRYPT);
            return $stmt->execute([
                ':full_name' => $data['full_name'],
                ':email'     => $data['email'],
                ':phone'     => $data['phone'],
                ':password'  => $password,
                ':activated' => $data['activated'],
                ':role'      => $data['role'],
                ':id'        => $account_id,
            ]);
        } else {
            $stmt = $this->db->prepare("
            UPDATE account
            SET full_name = :full_name,
                email = :email,
                phone = :phone,
                activated = :activated,
                role = :role
            WHERE account_id = :id
        ");
            return $stmt->execute([
                ':full_name' => $data['full_name'],
                ':email'     => $data['email'],
                ':phone'     => $data['phone'],
                ':activated' => $data['activated'],
                ':role'      => $data['role'],
                ':id'        => $account_id,
            ]);
        }
    }


    public function toggleActivated($account_id)
    {
        $account = $this->find($account_id);
        if (!$account) return false;
        $new = $account['activated'] ? 0 : 1;
        $stmt = $this->db->prepare("UPDATE account SET activated = :a WHERE account_id = :id");
        return $stmt->execute([':a' => $new, ':id' => $account_id]);
    }

    public function delete($account_id)
    {
        $stmt = $this->db->prepare("DELETE FROM account WHERE account_id = :id");
        return $stmt->execute([':id' => $account_id]);
    }

    public function findByEmaill($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM account WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createSignUp($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO account (password, full_name,phone, email, role, activated)
            VALUES (?, ?, ?,?, ?,?)
        ");
        return $stmt->execute([
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['full_name'],
            $data['phone'],
            $data['email'],
            $data['role'] ?? 'Customer',
            $data['activated'],
        ]);
    }
}
