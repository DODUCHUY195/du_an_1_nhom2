<?php
namespace App\Models;
use Core\Database, PDO;
class BaseModel {
  protected PDO $db; protected string $table='';
  public function __construct(array $cfg){ $this->db = Database::getInstance($cfg); }
  public function find($id){ $s=$this->db->prepare("SELECT * FROM {$this->table} WHERE id=:id"); $s->execute(['id'=>$id]); return $s->fetch(); }
  public function all(){ return $this->db->query("SELECT * FROM {$this->table} ORDER BY id DESC")->fetchAll(); }
  public
