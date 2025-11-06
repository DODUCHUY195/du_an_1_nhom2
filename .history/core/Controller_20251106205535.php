<?php
namespace Core;
class Controller {
  protected function view(string $p, array $d=[]){ Response::view($p,$d); }
  protected function json($d,int $s=200){ Response::json($d,$s); }
  protected function redirect(string $u){ Response::redirect($u); }
}
