<?php

class BookingController
{
    public function index()
    {
        header('Location: ' . BASE_URL . '?route=/bookings/create');
        exit;
    }

    public function create()
    {
        $scheduleModel = new Schedule();
        $bookingModel = new Booking();
        $userModel = new User();
        $tourModel = new Tour();

        $schedules = $scheduleModel->allWithTour();
        $error = null;
        $oldInput = [
            'customer_name'   => '',
            'customer_email'  => '',
            'customer_phone'  => '',
            'schedule_id'     => '',
            'passenger_count' => 1,
            'deposit_amount'  => 0,
            'payment_method'  => 'cash',
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customerName = trim($_POST['customer_name'] ?? '');
            $customerEmail = trim($_POST['customer_email'] ?? '');
            $customerPhone = trim($_POST['customer_phone'] ?? '');
            $scheduleId = (int)($_POST['schedule_id'] ?? 0);
            $passengerCount = (int)($_POST['passenger_count'] ?? 0);
            $depositAmount = (float)($_POST['deposit_amount'] ?? 0);
            $paymentMethod = trim($_POST['payment_method'] ?? '');

            $oldInput = [
                'customer_name'   => $customerName,
                'customer_email'  => $customerEmail,
                'customer_phone'  => $customerPhone,
                'schedule_id'     => $scheduleId,
                'passenger_count' => $passengerCount,
                'deposit_amount'  => $depositAmount,
                'payment_method'  => $paymentMethod,
            ];

            $errors = [];

            if ($customerName === '') {
                $errors[] = 'Họ tên khách hàng không được để trống.';
            }
            if (!filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email không hợp lệ.';
            }
            if ($customerPhone === '') {
                $errors[] = 'Vui lòng nhập số điện thoại.';
            }
            if ($scheduleId <= 0) {
                $errors[] = 'Vui lòng chọn lịch tour.';
            }
            if ($passengerCount <= 0) {
                $errors[] = 'Số lượng khách phải lớn hơn 0.';
            }
            if ($depositAmount < 0) {
                $errors[] = 'Tiền cọc không hợp lệ.';
            }

            $allowedPayments = ['cash', 'bank_transfer', 'card'];
            if (!in_array($paymentMethod, $allowedPayments, true)) {
                $errors[] = 'Phương thức thanh toán không hợp lệ.';
            }

            $schedule = $scheduleId > 0 ? $scheduleModel->find($scheduleId) : null;
            if (!$schedule) {
                $errors[] = 'Không tìm thấy lịch tour.';
            }

            $tour = null;
            if ($schedule && !empty($schedule['tour_id'])) {
                $tour = $tourModel->getDetailTour($schedule['tour_id']);
            }
            if (!$tour) {
                $errors[] = 'Không tìm thấy thông tin tour tương ứng.';
            }

            if (empty($errors)) {
                $availableSeats = $scheduleModel->getAvailableSeats($scheduleId);
                if ($passengerCount > $availableSeats) {
                    $errors[] = 'Số ghế trống không đủ cho số khách yêu cầu.';
                }
            }

            $customer = null;
            if (empty($errors)) {
                $customer = $userModel->findByEmail($customerEmail);
                if (!$customer) {
                    $randomPassword = bin2hex(random_bytes(8));
                    $userCreated = $userModel->create([
                        'full_name' => $customerName,
                        'email'     => $customerEmail,
                        'phone'     => $customerPhone,
                        'password'  => password_hash($randomPassword, PASSWORD_BCRYPT),
                        'role'      => 'customer',
                        'activated' => 0,
                    ]);
                    if (!$userCreated) {
                        $errors[] = 'Không thể tạo khách hàng mới. Vui lòng thử lại.';
                    } else {
                        $customer = $userModel->findByEmail($customerEmail);
                    }
                }
            }

            if (empty($errors) && !$customer) {
                $errors[] = 'Không thể xác định khách hàng.';
            }

            if (empty($errors)) {
                if ($bookingModel->hasDuplicate((int)$customer['user_id'], $scheduleId)) {
                    $errors[] = 'Khách đã có booking cho lịch trình này.';
                }
            }

            if (empty($errors)) {
                $price = (float)($tour['price'] ?? 0);
                $totalAmount = $price * $passengerCount;

                if ($totalAmount <= 0) {
                    $errors[] = 'Giá tour không hợp lệ.';
                }

                if ($depositAmount > $totalAmount) {
                    $errors[] = 'Tiền cọc không thể lớn hơn tổng tiền.';
                }

                if (empty($errors)) {
                    $status = $depositAmount > 0 ? 'confirmed' : 'pending';

                    try {
                        $bookingId = $bookingModel->create([
                            'customer_id'    => (int)$customer['user_id'],
                            'schedule_id'    => $scheduleId,
                            'total_amount'   => $totalAmount,
                            'deposit'        => $depositAmount,
                            'payment_method' => $paymentMethod,
                            'status'         => $status,
                        ]);

                        if ($bookingId <= 0) {
                            $errors[] = 'Không thể lưu booking.';
                        } else {
                            if (!$scheduleModel->adjustSeatsBooked($scheduleId, $passengerCount)) {
                                $bookingModel->updateStatus($bookingId, 'cancelled');
                                $errors[] = 'Không thể cập nhật số ghế cho lịch này.';
                            } elseif (!$bookingModel->updatePassengerCount($bookingId, $passengerCount)) {
                                $scheduleModel->adjustSeatsBooked($scheduleId, -$passengerCount);
                                $bookingModel->updateStatus($bookingId, 'pending');
                                $errors[] = 'Không thể lưu thông tin hành khách.';
                            } else {
                                $_SESSION['flash_success'] = 'Tạo booking thành công.';
                                header('Location: ' . BASE_URL . '?route=/admin/bookings');
                                exit;
                            }
                        }
                    } catch (Throwable $th) {
                        $errors[] = 'Có lỗi xảy ra khi tạo booking. Vui lòng thử lại.';
                    }
                }
            }

            if (!empty($errors)) {
                $error = implode('<br>', $errors);
            }
        }

        $flashSuccess = $_SESSION['flash_success'] ?? null;
        $flashError = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        require PATH_VIEW . '/bookings/create.php';
    }
}
