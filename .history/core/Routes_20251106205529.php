<?php
namespace Core;
class Router {
  protected Request $req; protected array $routes = [];
  public function __construct(Request $r){ $this->req = $r; }
  public function get($p,$a){ $this->routes[]=['GET',$p,$a]; }
  public function post($p,$a){ $this->routes[]=['POST',$p,$a]; }
  public function dispatch(){
    $uri = $this->req->uri; $method = $this->req->method;
    foreach($this->routes as $r){
      if($r[0]===$method && $r[1]===$uri){
        return $this->callAction($r[2]);
      }
    }
    http_response_code(404); echo "404"; exit;
  }
  protected function callAction(string $action){
    [$ctrl,$method]=explode('@',$action);
    $class = "\\App\\Controllers\\{$ctrl}";
    if(!class_exists($class)) throw new \Exception("Controller $class not found");
    return (new $class())->{$method}($this->req);
  }
}
