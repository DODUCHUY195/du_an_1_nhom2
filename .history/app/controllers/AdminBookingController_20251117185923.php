<?php
class AdminBookingController
{
    public function index()
    {
        $m = new Booking();
        $bookings = $m->all();
        $this->render('admin/bookings/index', ['bookings' => $bookings]);
    }
    public function updateStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['booking_id'];
            $status = $_POST['status'];
            $stmt = (new Booking())->db->prepare('UPDATE booking SET status = :status WHERE booking_id = :id');
            $stmt->execute([':status' => $status, ':id' => $id]);
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Trạng thái cập nhật'];
            $this->redirect('/admin/bookings');
        }
    }
}
