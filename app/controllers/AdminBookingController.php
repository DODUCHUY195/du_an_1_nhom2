<?php

class AdminBookingController
{
    public function index()
    {
        $filters = [
            'tour_id'     => isset($_GET['tour_id']) && $_GET['tour_id'] !== '' ? (int)$_GET['tour_id'] : null,
            'schedule_id' => isset($_GET['schedule_id']) && $_GET['schedule_id'] !== '' ? (int)$_GET['schedule_id'] : null,
            'status'      => isset($_GET['status']) && $_GET['status'] !== '' ? trim($_GET['status']) : null,
        ];

        $bookingModel = new Booking();
        $bookings = $bookingModel->getAllWithRelations($filters);

        $tours = (new Tour())->getAllTour();
        $schedules = (new Schedule())->allWithTour();

        $flashSuccess = $_SESSION['flash_success'] ?? null;
        $flashError = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        require PATH_VIEW . '/bookings/index.php';
    }

    public function updateStatus(?array $postData = null)
    {
        $payload = $postData ?? $_POST;

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST' && $postData === null) {
            header('Location: ' . BASE_URL . '?route=/admin/bookings');
            return;
        }

        $bookingId = (int)($payload['booking_id'] ?? 0);
        $newStatus = trim($payload['status'] ?? '');
        $allowedStatuses = ['pending', 'confirmed', 'cancelled', 'refunded'];

        if ($bookingId <= 0 || !in_array($newStatus, $allowedStatuses, true)) {
            $_SESSION['flash_error'] = 'Dữ liệu cập nhật không hợp lệ.';
            header('Location: ' . BASE_URL . '?route=/admin/bookings');
            exit;
        }

        $bookingModel = new Booking();
        $booking = $bookingModel->findById($bookingId);

        if (!$booking) {
            $_SESSION['flash_error'] = 'Không tìm thấy booking cần cập nhật.';
            header('Location: ' . BASE_URL . '?route=/admin/bookings');
            exit;
        }

        $currentStatus = $booking['status'];
        if ($currentStatus === $newStatus) {
            $_SESSION['flash_success'] = 'Trạng thái không thay đổi.';
            header('Location: ' . BASE_URL . '?route=/admin/bookings');
            exit;
        }

        $scheduleId = (int)($booking['schedule_id'] ?? 0);
        $passengerCount = $bookingModel->getPassengerCount($bookingId);
        $scheduleModel = new Schedule();

        if ($scheduleId <= 0) {
            $_SESSION['flash_error'] = 'Booking không có lịch hợp lệ.';
            header('Location: ' . BASE_URL . '?route=/admin/bookings');
            exit;
        }

        // Handle seat adjustments based on status transitions.
        if (in_array($newStatus, ['cancelled', 'refunded'], true) && in_array($currentStatus, ['pending', 'confirmed'], true)) {
            if ($passengerCount > 0 && !$scheduleModel->adjustSeatsBooked($scheduleId, -$passengerCount)) {
                $_SESSION['flash_error'] = 'Không thể trả ghế cho lịch này.';
                header('Location: ' . BASE_URL . '?route=/admin/bookings');
                exit;
            }
        }

        if ($newStatus === 'confirmed' && in_array($currentStatus, ['cancelled', 'refunded'], true)) {
            $availableSeats = $scheduleModel->getAvailableSeats($scheduleId);
            if ($passengerCount > $availableSeats) {
                $_SESSION['flash_error'] = 'Không đủ ghế để xác nhận lại booking.';
                header('Location: ' . BASE_URL . '?route=/admin/bookings');
                exit;
            }
            if ($passengerCount > 0 && !$scheduleModel->adjustSeatsBooked($scheduleId, $passengerCount)) {
                $_SESSION['flash_error'] = 'Không thể giữ ghế cho booking này.';
                header('Location: ' . BASE_URL . '?route=/admin/bookings');
                exit;
            }
        }

        $updated = $bookingModel->updateStatus($bookingId, $newStatus);
        if ($updated) {
            $_SESSION['flash_success'] = 'Cập nhật trạng thái booking thành công.';
        } else {
            $_SESSION['flash_error'] = 'Không thể cập nhật trạng thái booking.';
        }

        header('Location: ' . BASE_URL . '?route=/admin/bookings');
        exit;
    }
}
