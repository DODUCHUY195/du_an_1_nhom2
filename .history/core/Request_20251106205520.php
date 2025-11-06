<?php
namespace Core;
class Request {
  public string $method; public string $uri; public array $get; public array $post;
  public function __construct() {
    $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $this->uri = rtrim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/') ?: '/';
    $this->get = $_GET; $this->post = $_POST;
  }
  public static function capture(): self { return new self(); }
  public function input($k, $d=null) { return $this->post[$k] ?? $this->get[$k] ?? $d; }
}
