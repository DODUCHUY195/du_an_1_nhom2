<?php
class BaseController { 
    protected function render($view,$data=[]){
         extract($data);
          $flash = isset($_SESSION['flash'])?$_SESSION['flash']:null; 
          require_once __DIR__ . '/../views/layouts/header.php'; 
          require_once __DIR__ . '/../views/' . $view . '.php';
           require_once __DIR__ . '/../views/layouts/footer.php'; 
        }
         protected function redirect($url){
             header('Location: '.$url); 
             exit; 
            } 
        }
