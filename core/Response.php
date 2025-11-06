<?php
namespace Core;
class Response {
  public static function view(string $path, array $data = []) {
    extract($data); require __DIR__ . "/../app/Views/{$path}.php"; exit;
  }
  public static function json($d,int $s=200){ header('Content-Type:application/json',$s); echo json_encode($d); exit;}
  public static function redirect(string $u){ header('Location:'.$u); exit;}
}
