<?php

class AdminBookingController
{
    private Booking $bookingModel;

    public function __construct()
    {
        $this->bookingModel = new Booking();
    }

    public function index()
    {
        $filters = [
            'tour_id'     => isset($_GET['tour_id']) && $_GET['tour_id'] !== '' ? (int)$_GET['tour_id'] : null,
            'schedule_id' => isset($_GET['schedule_id']) && $_GET['schedule_id'] !== '' ? (int)$_GET['schedule_id'] : null,
            'status'      => isset($_GET['status']) && $_GET['status'] !== '' ? trim($_GET['status']) : null,
        ];

        $bookings = $this->bookingModel->getAllWithRelations($filters);
        $counts = $this->bookingModel->countBySource();

        $tours = (new Tour())->getAllTour();
        $schedules = (new Schedule())->getSchedulesWithSeats();

        $flashSuccess = $_SESSION['flash_success'] ?? null;
        $flashError = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        require PATH_VIEW . '/bookings/index.php';
    }

    public function create()
    {
        $scheduleModel = new Schedule();
        $accountModel = new Account();
        $vehicleModel = new Vehicle();
        $tourModel = new Tour();

        $schedules = $scheduleModel->getSchedulesWithSeats();
        $vehiclesBySchedule = [];
        foreach ($schedules as $schedule) {
            $vehiclesBySchedule[$schedule['schedule_id']] = $vehicleModel->getBySchedule((int)$schedule['schedule_id']);
        }

        $error = null;
        $oldInput = $_POST ?? [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $form = [
                'full_name'       => trim($_POST['customer_name'] ?? ''),
                'email'           => trim($_POST['customer_email'] ?? ''),
                'phone'           => trim($_POST['customer_phone'] ?? ''),
                'schedule_id'     => (int)($_POST['schedule_id'] ?? 0),
                'vehicle_id'      => isset($_POST['vehicle_id']) && $_POST['vehicle_id'] !== '' ? (int)$_POST['vehicle_id'] : null,
                'passenger_count' => (int)($_POST['passenger_count'] ?? 0),
                'deposit'         => (float)($_POST['deposit'] ?? 0),
                'payment_method'  => strtolower(trim($_POST['payment_method'] ?? '')),
                'status'          => trim($_POST['status'] ?? 'pending'),
            ];

            $errors = [];

            if ($form['full_name'] === '') {
                $errors[] = 'Họ tên khách hàng không được để trống.';
            }
            if (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email khách hàng không hợp lệ.';
            }
            if ($form['phone'] === '') {
                $errors[] = 'Vui lòng nhập số điện thoại khách hàng.';
            }
            if ($form['schedule_id'] <= 0) {
                $errors[] = 'Vui lòng chọn lịch khởi hành.';
            }
            if ($form['passenger_count'] <= 0) {
                $errors[] = 'Số lượng khách phải lớn hơn 0.';
            }
            if ($form['deposit'] < 0) {
                $errors[] = 'Tiền cọc không hợp lệ.';
            }

            $allowedPayments = ['cash', 'bank_transfer', 'credit_card', 'ewallet'];
            if (!in_array($form['payment_method'], $allowedPayments, true)) {
                $errors[] = 'Phương thức thanh toán không hợp lệ.';
            }

            $allowedStatuses = ['pending', 'confirmed', 'cancelled'];
            if (!in_array($form['status'], $allowedStatuses, true)) {
                $errors[] = 'Trạng thái booking không hợp lệ.';
            }

            $schedule = null;
            $tour = null;
            if (empty($errors)) {
                $schedule = $scheduleModel->getById($form['schedule_id']);
                if (!$schedule) {
                    $errors[] = 'Không tìm thấy lịch khởi hành.';
                } elseif ($schedule['status'] !== 'open') {
                    $errors[] = 'Lịch khởi hành đã đóng, không thể đặt thêm.';
                } else {
                    $tour = $tourModel->getDetailTour($schedule['tour_id']);
                    if (!$tour) {
                        $errors[] = 'Không tìm thấy thông tin tour cho lịch này.';
                    }
                }
            }

            $availableSeats = 0;
            if (empty($errors) && $schedule) {
                $availableSeats = (int)$schedule['seats_total'] - (int)$schedule['seats_booked'];
                if ($form['passenger_count'] > $availableSeats) {
                    $errors[] = 'Số ghế trống trong lịch không đủ.';
                }
            }

            $selectedVehicle = null;
            if (empty($errors) && $form['vehicle_id']) {
                $vehicles = $vehicleModel->getBySchedule($form['schedule_id']);
                foreach ($vehicles as $vehicle) {
                    if ((int)$vehicle['vehicle_id'] === $form['vehicle_id']) {
                        $selectedVehicle = $vehicle;
                        break;
                    }
                }

                if (!$selectedVehicle) {
                    $errors[] = 'Không tìm thấy xe đã chọn.';
                } elseif ($form['passenger_count'] > (int)$selectedVehicle['available_seats']) {
                    $errors[] = 'Số ghế trống của xe không đủ cho số khách.';
                }
            }

            $customer = null;
            if (empty($errors)) {
                $customer = $accountModel->findByEmail($form['email']);
                if ($customer && strcasecmp($customer['role'], 'Customer') !== 0) {
                    $errors[] = 'Email này đã thuộc về tài khoản không phải khách hàng.';
                }

                if (!$customer) {
                    $randomPassword = bin2hex(random_bytes(6));
                    $accountModel->create([
                        'full_name' => $form['full_name'],
                        'email'     => $form['email'],
                        'phone'     => $form['phone'],
                        'password'  => $randomPassword,
                        'activated' => 1,
                        'role'      => 'Customer',
                    ]);
                    $customer = $accountModel->findByEmail($form['email']);
                }
            }

            if (empty($errors) && !$customer) {
                $errors[] = 'Không thể tạo tài khoản khách hàng.';
            }

            if (empty($errors) && $this->bookingModel->hasDuplicate((int)$customer['account_id'], $form['schedule_id'])) {
                $errors[] = 'Khách hàng đã có booking cho lịch này.';
            }

            $totalAmount = 0;
            if (empty($errors) && $tour) {
                $totalAmount = (float)$tour['price'] * $form['passenger_count'];
                if ($totalAmount <= 0) {
                    $errors[] = 'Giá tour không hợp lệ.';
                } elseif ($form['deposit'] > $totalAmount) {
                    $errors[] = 'Tiền cọc không thể lớn hơn tổng tiền.';
                }
            }

            if (empty($errors) && $customer) {
                $bookingId = $this->bookingModel->create([
                    'customer_id'    => (int)$customer['account_id'],
                    'schedule_id'    => $form['schedule_id'],
                    'vehicle_id'     => $form['vehicle_id'],
                    'total_amount'   => $totalAmount,
                    'deposit'        => $form['deposit'],
                    'payment_method' => $form['payment_method'],
                    'status'         => $form['status'],
                    'booking_source' => 'admin',
                    'passenger_count'=> $form['passenger_count'],
                ]);

                if ($bookingId > 0) {
                    $scheduleModel->increaseBookedSeats($form['schedule_id'], $form['passenger_count']);
                    if ($form['vehicle_id']) {
                        $vehicleModel->increaseBookedSeats($form['vehicle_id'], $form['passenger_count']);
                    }

                    $_SESSION['flash_success'] = 'Tạo booking thành công.';
                    header('Location: ' . BASE_URL . '?route=/bookings');
                    exit;
                }

                $errors[] = 'Không thể tạo booking mới.';
            }

            if (!empty($errors)) {
                $error = implode('<br>', $errors);
                $oldInput = $form;
            }
        }

        require PATH_VIEW . '/bookings/create.php';
    }

    public function updateStatus(?array $postData = null)
    {
        $payload = $postData ?? $_POST;

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST' && $postData === null) {
            header('Location: ' . BASE_URL . '?route=/bookings');
            return;
        }

        $bookingId = (int)($payload['booking_id'] ?? 0);
        $newStatus = trim($payload['status'] ?? '');
        $allowedStatuses = ['pending', 'confirmed', 'cancelled'];

        if ($bookingId <= 0 || !in_array($newStatus, $allowedStatuses, true)) {
            $_SESSION['flash_error'] = 'Dữ liệu cập nhật không hợp lệ.';
            header('Location: ' . BASE_URL . '?route=/bookings');
            exit;
        }

        $booking = $this->bookingModel->findById($bookingId);
        if (!$booking) {
            $_SESSION['flash_error'] = 'Không tìm thấy booking cần cập nhật.';
            header('Location: ' . BASE_URL . '?route=/bookings');
            exit;
        }

        $currentStatus = $booking['status'];
        if ($currentStatus === $newStatus) {
            $_SESSION['flash_success'] = 'Trạng thái không thay đổi.';
            header('Location: ' . BASE_URL . '?route=/bookings');
            exit;
        }

        $updated = $this->bookingModel->updateStatus($bookingId, $newStatus);
        if ($updated) {
            if ($newStatus === 'cancelled' && $currentStatus !== 'cancelled') {
                $this->releaseResources($booking);
            }
            $_SESSION['flash_success'] = 'Cập nhật trạng thái booking thành công.';
        } else {
            $_SESSION['flash_error'] = 'Không thể cập nhật trạng thái booking.';
        }

        header('Location: ' . BASE_URL . '?route=/bookings');
        exit;
    }

    public function cancel($bookingId = null)
    {
        $bookingId = (int)($bookingId ?? 0);
        if ($bookingId <= 0) {
            $_SESSION['flash_error'] = 'Thiếu mã booking cần hủy.';
            header('Location: ' . BASE_URL . '?route=/bookings');
            exit;
        }

        $booking = $this->bookingModel->findById($bookingId);
        if (!$booking) {
            $_SESSION['flash_error'] = 'Không tìm thấy booking.';
            header('Location: ' . BASE_URL . '?route=/bookings');
            exit;
        }

        if ($booking['status'] === 'cancelled') {
            $_SESSION['flash_success'] = 'Booking đã hủy trước đó.';
            header('Location: ' . BASE_URL . '?route=/bookings');
            exit;
        }

        if ($this->bookingModel->updateStatus($bookingId, 'cancelled')) {
            $this->releaseResources($booking);
            $_SESSION['flash_success'] = 'Đã hủy booking và trả ghế thành công.';
        } else {
            $_SESSION['flash_error'] = 'Không thể hủy booking.';
        }

        header('Location: ' . BASE_URL . '?route=/bookings');
        exit;
    }

    private function releaseResources(array $booking): void
    {
        $scheduleModel = new Schedule();
        $vehicleModel = new Vehicle();

        $passengerCount = (int)($booking['passenger_count'] ?? 0);
        if ($passengerCount <= 0) {
            return;
        }

        if (!empty($booking['schedule_id'])) {
            $scheduleModel->decreaseBookedSeats((int)$booking['schedule_id'], $passengerCount);
        }

        if (!empty($booking['vehicle_id'])) {
            $vehicleModel->decreaseBookedSeats((int)$booking['vehicle_id'], $passengerCount);
        }
    }
}
