<?php
namespace Core;
use PDO;
class Database {
  private static ?PDO $pdo = null;
  public static function getInstance(array $cfg=[]): PDO {
    if(self::$pdo===null){
      $dsn = $cfg['dsn'] ?? ''; $u = $cfg['user'] ?? null; $p = $cfg['pass'] ?? null;
      $opts = $cfg['options'] ?? [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC];
      self::$pdo = new PDO($dsn,$u,$p,$opts);
    }
    return self::$pdo;
  }
}
