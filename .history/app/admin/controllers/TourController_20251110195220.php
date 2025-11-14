<?php
class TourController extends BaseController {
     public function index(){
         $m=new Tour(); $tours=$m->all(); $this->render('tours/index',['tours'=>$tours]);
         }
          public function create(){ 
            $catM=new Category(); $cats=$catM->all();
             if($_SERVER['REQUEST_METHOD']==='POST'){
                 $data=[
                    ':category_id'=>$_POST['category_id']??null,
                    ':tour_code'=>$_POST['tour_code'],
                    ':tour_name'=>$_POST['tour_name'],
                    ':price'=>$_POST['price'],
                    ':duration_days'=>$_POST['duration_days'],
                    ':description'=>$_POST['description'],
                    ':status'=>$_POST['status'] ?? 'draft'];
                     $m=new Tour();
                      $m->insert($data);
                       $_SESSION['flash']=['type'=>'success','msg'=>'Tour được tạo'];
                        $this->redirect('/tours'); 
                    }
                     $this->render('tours/form',['categories'=>$cats]);
         }
        }
