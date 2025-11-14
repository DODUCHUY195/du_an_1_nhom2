<?php
class ScheduleController extends BaseController {
    public function index(){
        $m = new Schedule();
        $schedules = $m->all();
        $this->render('schedules/index',['schedules'=>$schedules]);
    }
    public function create(){
        $tourM = new Tour(); $tours = $tourM->all();
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $data = [
                ':tour_id'=>$_POST['tour_id'],
                ':depart_date'=>$_POST['depart_date'],
                ':meeting_point'=>$_POST['meeting_point'],
                ':seats_total'=>$_POST['seats_total'],
                ':status'=>$_POST['status'] ?? 'open'
            ];
            $m = new Schedule(); $m->create($data);
            $_SESSION['flash']=['type'=>'success','msg'=>'Lịch khởi hành được tạo'];
            $this->redirect('/schedules');
        }
        $this->render('schedules/form',['tours'=>$tours]);
    }
    public function edit($id){
        $m = new Schedule(); $schedule = $m->find($id);
        $tourM = new Tour(); $tours = $tourM->all();
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $data = [
                ':tour_id'=>$_POST['tour_id'],
                ':depart_date'=>$_POST['depart_date'],
                ':meeting_point'=>$_POST['meeting_point'],
                ':seats_total'=>$_POST['seats_total'],
                ':status'=>$_POST['status'] ?? 'open'
            ];
            $m->update($id,$data);
            $_SESSION['flash']=['type'=>'success','msg'=>'Lịch đã được cập nhật'];
            $this->redirect('/schedules');
        }
        $this->render('schedules/form',['schedule'=>$schedule,'tours'=>$tours]);
    }
}
?>