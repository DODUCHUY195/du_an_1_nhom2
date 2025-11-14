<?php
class BookingController extends BaseController {
    public function index(){ 
        $m = new Booking();
         $bookings = $m->all(); 
         $this->render('bookings/index',['bookings'=>$bookings]);
         }
    public function create(){
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $schedule_id = (int)$_POST['schedule_id'];
            $num_adults = (int)$_POST['num_adults'];
            $num_children = (int)$_POST['num_children'];
            $total_people = $num_adults + $num_children;
            $db = (new Booking())->db;
            try {
                $db->beginTransaction();
                // check schedule
                $stmt = $db->prepare('SELECT * FROM tour_schedule WHERE schedule_id = :id FOR UPDATE');
                $stmt->execute([':id'=>$schedule_id]);
                $schedule = $stmt->fetch();
                if(!$schedule || $schedule['status'] !== 'open'){
                    throw new Exception('Schedule not available');
                }
                $available = $schedule['seats_total'] - $schedule['seats_booked'];
                if($available < $total_people) throw new Exception('Không đủ chỗ');
                // create booking
                $booking_code = 'BK'.time();
                $stmt2 = $db->prepare('INSERT INTO booking (booking_code,schedule_id,customer_id,num_adults,num_children,total_amount,deposit_amount,status) VALUES(:booking_code,:schedule_id,:customer_id,:num_adults,:num_children,:total_amount,:deposit_amount,:status)');
                $stmt2->execute([':booking_code'=>$booking_code,':schedule_id'=>$schedule_id,':customer_id'=>$_SESSION['user']['user_id'],':num_adults'=>$num_adults,':num_children'=>$num_children,':total_amount'=>$_POST['total_amount'],':deposit_amount'=>$_POST['deposit_amount'],':status'=>'pending']);
                // update seats_booked
                $stmt3 = $db->prepare('UPDATE tour_schedule SET seats_booked = seats_booked + :cnt WHERE schedule_id = :id');
                $stmt3->execute([':cnt'=>$total_people,':id'=>$schedule_id]);
                $db->commit();
                $_SESSION['flash']=['type'=>'success','msg'=>'Booking tạo thành công'];
                $this->redirect('/bookings');
            } catch(Exception $e){
                $db->rollBack();
                $_SESSION['flash']=['type'=>'danger','msg'=>'Lỗi: '.$e->getMessage()];
            }
        }
        $this->render('bookings/form');
    }
}
?>