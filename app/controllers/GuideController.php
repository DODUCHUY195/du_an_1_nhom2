<?php
class GuideController
{

    private $model;

    public function __construct()
    {
        $db = connectDB(); // dùng hàm connectDB trong function.php
        $this->model = new Guide($db);
    }

    // Hiển thị danh sách HDV
    public function index()
    {
        $guides = $this->model->getAllGuides();
        require_once './views/guides/index.php';
    }

    // Form tạo hồ sơ HDV
    public function create()
    {


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'full_name'    => $_POST['full_name'],
                'birth_date' => $_POST['birth_date'],
                'contact'    => $_POST['contact'],
                'license_no' => $_POST['license_no'],
                'photo'      => $_FILES['photo']['name'] ?? null,
                'note'       => $_POST['note'] ?? ''
            ];
            // ✅ Xử lý upload ảnh
            if (!empty($_FILES['photo']['name'])) {
                $filename = time() . '_' . basename($_FILES['photo']['name']); // tránh trùng tên
                $targetPath = './uploads/' . $filename;

                if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
                    $data['photo'] = $filename;
                }
            }
            $users = $this->model->getAllbooking_customer(); // bạn cần có model users

            $this->model->createGuide($data);
            header("Location: " . BASE_URL . "?route=/guides");
            exit;
        } else {
            require_once './views/guides/create.php';
        }
    }

    // Form sửa thông tin HDV
    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'full_name' => $_POST['full_name'],
                'birth_date' => $_POST['birth_date'],
                'contact'    => $_POST['contact'],
                'license_no' => $_POST['license_no'],
                'photo'      => $_FILES['photo']['name'] ?? null,
                'note'       => $_POST['note'] ?? ''
            ];
            // Lấy ảnh cũ từ DB
            $guide = $this->model->getGuideById($id);
            $oldPhoto = $guide['photo'];

            // ✅ Xử lý ảnh mới nếu có
            if (!empty($_FILES['photo']['name'])) {
                $filename = time() . '_' . basename($_FILES['photo']['name']);
                $targetPath = './uploads/' . $filename;

                if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
                    $data['photo'] = $filename;
                } else {
                    $data['photo'] = $oldPhoto; // nếu upload lỗi → giữ ảnh cũ
                }
            } else {
                $data['photo'] = $oldPhoto; // nếu không chọn ảnh mới → giữ ảnh cũ
            }
            $this->model->updateGuide($id, $data);
            header("Location: " . BASE_URL . "?route=/guides");
            exit;
        } else {
            $guide = $this->model->getGuideById($id);
            require_once './views/guides/edit.php';
        }
    }

    // Xóa HDV
    public function delete($id)
    {
        $this->model->deleteGuide($id);
        header("Location: " . BASE_URL . "?route=/guides");
        exit;
    }

    // Phân công HDV cho tour
    public function assign($guide_id)
    {
        $schedules = $this->model->getAllSchedules();
        // Kiểm tra nếu HDV đã được phân công
        $existing = $this->model->getAssignmentByGuide($guide_id);
        if ($existing) {
            echo "<script>alert('HDV này đã được phân công cho tour #{$existing['schedule_id']}'); window.location.href='" . BASE_URL . "?route=/guides';</script>";
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $schedule_id = $_POST['schedule_id'];
            $schedules = $this->model->assignGuide($guide_id, $schedule_id);
            header("Location: " . BASE_URL . "?route=/guides");
            exit;
        } else {
            require_once './views/guides/assign.php';
        }
    }
}
