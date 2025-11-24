<?php
function set_flash($type, $msg){
     $_SESSION['flash'] = ['type'=>$type,'msg'=>$msg]; 
    }
function get_flash(){ 
    if(isset($_SESSION['flash'])){ $f = $_SESSION['flash']; unset($_SESSION['flash']); return $f;} return null;
 }
function auth_user(){
     return isset($_SESSION['user']) ? $_SESSION['user'] : null; 
    }
