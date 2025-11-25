<?php
class BookingController
{
    public function index()
    {
        $booking = new Booking();
        $list = $booking->getAll();
        require_once './views/bookings/index.php';
    }

    public function detail()
    {
        $booking_id = $_GET['booking_id'];
        $booking = new Booking();
        $detail = $booking->getById($booking_id);
        require_once './views/bookings/detail.php';
    }

    public function postCreate()
    {
        $customer_id = $_POST['customer_id'];
        $schedule_id = $_POST['schedule_id'];
        $amount = $_POST['amount'];

        $schedule = new Schedule();
        if (!$schedule->hasSeats($schedule_id, 1)) die("Hết ghế!");

        $booking = new Booking();
        $booking_id = $booking->create($customer_id, $schedule_id, $amount);
        $schedule->updateSeats($schedule_id, 1);

        header("Location: ?route=/bookings/detail&booking_id=".$booking_id);
    }

    public function approve()
    {
        $booking_id = $_GET['booking_id'];
        $booking = new Booking();
        $booking->updateStatus($booking_id, "approved");
        header("Location: ?route=/admin/bookings");
    }

    public function cancel()
    {
        $booking_id = $_GET['booking_id'];
        $booking = new Booking();
        $booking->updateStatus($booking_id, "cancelled");
        header("Location: ?route=/admin/bookings");
    }

    public function payDeposit()
    {
        $booking_id = $_POST['booking_id'];
        $deposit = $_POST['deposit'];

        $booking = new Booking();
        $booking->updateDeposit($booking_id, $deposit);
        $booking->updateStatus($booking_id, "paid");

        header("Location: ?route=/bookings/detail&booking_id=".$booking_id);
    }
}
