<?php
class HomeController extends BaseController {
    public function home(){
        
         $this->render('admin/bookings/index',['bookings'=>$bookings]);
    }
    
}
?>