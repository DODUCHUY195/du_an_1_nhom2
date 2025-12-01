<?php

class BookingController
{
    private Booking $bookingModel;
    private Schedule $scheduleModel;
    private Account $accountModel;
    private Vehicle $vehicleModel;
    private Tour $tourModel;
    private BookingCustomer $bookingCustomerModel;

    public function __construct()
    {
        $this->bookingModel = new Booking();
        $this->scheduleModel = new Schedule();
        $this->accountModel = new Account();
        $this->vehicleModel = new Vehicle();
        $this->tourModel = new Tour();
        $this->bookingCustomerModel = new BookingCustomer();
    }

    public function customerCreate()
    {
        $schedules = $this->scheduleModel->getSchedulesWithSeats();
        $error = $_SESSION['flash_error'] ?? null;
        $success = $_SESSION['flash_success'] ?? null;
        $oldInput = $_SESSION['old_customer_booking'] ?? [];

        unset($_SESSION['flash_error'], $_SESSION['flash_success'], $_SESSION['old_customer_booking']);

        require './views/bookings/customer_create.php';
    }

    public function storeFromCustomer(array $post = [])
    {
        $payload = $post ?: $_POST;

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST' && $post === []) {
            header('Location: ' . BASE_URL . '?route=/booking/customer');
            return;
        }

        $data = [
            'full_name'       => trim($payload['full_name'] ?? ''),
            'email'           => trim($payload['email'] ?? ''),
            'phone'           => trim($payload['phone'] ?? ''),
            'schedule_id'     => (int)($payload['schedule_id'] ?? 0),
            'passenger_count' => (int)($payload['passenger_count'] ?? 0),
            'payment_method'  => strtolower(trim($payload['payment_method'] ?? '')),
            'special_request' => trim($payload['special_request'] ?? ''),
        ];

        $errors = [];

        if ($data['full_name'] === '') {
            $errors[] = 'Vui lòng nhập họ tên.';
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email không hợp lệ.';
        }
        if ($data['phone'] === '') {
            $errors[] = 'Vui lòng nhập số điện thoại.';
        }
        if ($data['schedule_id'] <= 0) {
            $errors[] = 'Bạn cần chọn lịch tour.';
        }
        if ($data['passenger_count'] <= 0) {
            $errors[] = 'Số lượng khách phải lớn hơn 0.';
        }

        $allowedPayments = ['ewallet', 'credit_card', 'bank_transfer'];
        if (!in_array($data['payment_method'], $allowedPayments, true)) {
            $errors[] = 'Phương thức thanh toán không hợp lệ.';
        }

        $schedule = null;
        $tour = null;
        if (empty($errors)) {
            $schedule = $this->scheduleModel->getById($data['schedule_id']);
            if (!$schedule) {
                $errors[] = 'Không tìm thấy lịch tour.';
            } elseif ($schedule['status'] !== 'open') {
                $errors[] = 'Lịch tour này đã đóng.';
            } else {
                $tour = $this->tourModel->getDetailTour($schedule['tour_id']);
            }
        }

        $availableSeats = 0;
        if (empty($errors) && $schedule) {
            $availableSeats = (int)$schedule['seats_total'] - (int)$schedule['seats_booked'];
            if ($data['passenger_count'] > $availableSeats) {
                $errors[] = 'Số ghế còn trống không đủ.';
            }
        }

        $selectedVehicle = null;
        if (empty($errors) && $schedule) {
            $vehicles = $this->vehicleModel->getBySchedule($data['schedule_id']);
            if (!empty($vehicles)) {
                foreach ($vehicles as $vehicle) {
                    $availableVehicleSeats = (int)$vehicle['available_seats'];
                    if ($availableVehicleSeats >= $data['passenger_count']) {
                        $selectedVehicle = $vehicle;
                        break;
                    }
                }
                if (!$selectedVehicle) {
                    $errors[] = 'Các xe của lịch này không đủ ghế cho số khách bạn chọn.';
                }
            }
        }

        $customer = null;
        if (empty($errors)) {
            $customer = $this->accountModel->findByEmail($data['email']);
            if ($customer && strcasecmp($customer['role'], 'Customer') !== 0) {
                $errors[] = 'Email này đã thuộc tài khoản khác.';
            }
            if (!$customer) {
                $randomPassword = bin2hex(random_bytes(6));
                $created = $this->accountModel->create([
                    'full_name' => $data['full_name'],
                    'email'     => $data['email'],
                    'phone'     => $data['phone'],
                    'password'  => $randomPassword,
                    'activated' => 1,
                    'role'      => 'Customer',
                ]);
                if ($created) {
                    $customer = $this->accountModel->findByEmail($data['email']);
                } else {
                    $errors[] = 'Không thể tạo tài khoản khách hàng.';
                }
            }
        }

        if (empty($errors) && $customer && $this->bookingModel->hasDuplicate((int)$customer['account_id'], $data['schedule_id'])) {
            $errors[] = 'Bạn đã có booking cho lịch này.';
        }

        $totalAmount = 0;
        if (empty($errors) && $tour) {
            $totalAmount = (float)$tour['price'] * $data['passenger_count'];
            if ($totalAmount <= 0) {
                $errors[] = 'Không thể xác định giá tour.';
            }
        }

        if (!empty($errors)) {
            $_SESSION['flash_error'] = implode('<br>', $errors);
            $_SESSION['old_customer_booking'] = $data;
            header('Location: ' . BASE_URL . '?route=/booking/customer');
            exit;
        }

        $vehicleId = $selectedVehicle['vehicle_id'] ?? null;
        $deposit = $totalAmount;

        $bookingId = $this->bookingModel->create([
            'customer_id'    => (int)$customer['account_id'],
            'schedule_id'    => $data['schedule_id'],
            'vehicle_id'     => $vehicleId,
            'total_amount'   => $totalAmount,
            'deposit'        => $deposit,
            'payment_method' => $data['payment_method'],
            'status'         => 'pending',
            'booking_source' => 'customer',
            'passenger_count'=> $data['passenger_count'],
        ]);

        if ($bookingId <= 0) {
            $_SESSION['flash_error'] = 'Không thể tạo booking, vui lòng thử lại.';
            $_SESSION['old_customer_booking'] = $data;
            header('Location: ' . BASE_URL . '?route=/booking/customer');
            exit;
        }

        $this->scheduleModel->increaseBookedSeats($data['schedule_id'], $data['passenger_count']);
        if ($vehicleId) {
            $this->vehicleModel->increaseBookedSeats($vehicleId, $data['passenger_count']);
        }

        if (!empty($data['special_request'])) {
            $this->bookingCustomerModel->addCustomer([
                'booking_id' => $bookingId,
                'full_name' => $data['full_name'],
                'gender' => 'Khác',
                'birth_year' => null,
                'id_number' => null,
                'special_request' => $data['special_request'],
            ]);
        }

        $_SESSION['flash_success'] = 'Đặt tour thành công! Chúng tôi sẽ liên hệ để xác nhận.';
        header('Location: ' . BASE_URL . '?route=/booking/customer');
        exit;
    }
}
