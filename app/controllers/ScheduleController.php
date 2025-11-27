<?php
class ScheduleController
{
    // ==========================
    // 1 QUẢN LÝ LỊCH KHỞI HÀNH
    // ==========================

    // Danh sách schedule
    public function index()
    {
        $model = new Schedule();
        
        // Get current page number
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page); // Ensure page is at least 1
        
        // Items per page
        $itemsPerPage = 5;
        
        // Get filter parameters
        $tour_id = $_GET['tour_id'] ?? '';
        $status = $_GET['status'] ?? '';
        $search = $_GET['search'] ?? '';
        
        // Get schedules with pagination and filters
        $result = $model->getSchedulesWithFilters($tour_id, $status, $search, $page, $itemsPerPage);
        $list = $result['schedules'];
        $totalSchedules = $result['total'];
        $totalPages = $result['totalPages'];
        
        // Get all tours for filter dropdown
        $tourModel = new Tour();
        $tours = $tourModel->getAllTour();
        
        // Get all guides for assignment
        $guideModel = new Guide();
        $guides = $guideModel->all();
        
        require "./views/schedules/index.php";
    }
    // Form thêm schedule
    public function addForm()
    {
        $tourModel = new Tour();
        $tours = $tourModel->getAllTour();
        
        // Get all guides for assignment
        $guideModel = new Guide();
        $guides = $guideModel->all();

        require "./views/schedules/addForm.php";
    }

    // Lưu schedule
    public function postAdd()
    {
        $data = [
            'tour_id'       => $_POST['tour_id'],
            'depart_date'   => $_POST['depart_date'],
            'return_date'   => $_POST['return_date'] ?? null,
            'meeting_point' => $_POST['meeting_point'],
            'seats_total'   => $_POST['seats_total'],
            'seats_booked'  => $_POST['seats_booked'],
            'status'        => 'pending'
        ];

        $model = new Schedule();
        $schedule_id = $model->create($data);
        
        // Handle guide assignment if provided and schedule was created successfully
        if ($schedule_id && isset($_POST['guide_id']) && !empty($_POST['guide_id'])) {
            $guide_id = $_POST['guide_id'];
            $assign = new GuideAssignment();
            $assign->assignGuide($schedule_id, $guide_id);
        }

        header("Location: ?route=/schedules");
    }

    // Form sửa schedule
    public function editForm()
    {
        $schedule_id = $_GET['schedule_id'];

        $model = new Schedule();
        $schedule = $model->getById($schedule_id);

        $tourModel = new Tour();
        $tours = $tourModel->getAllTour();
        
        // Get all guides for assignment
        $guideModel = new Guide();
        $guides = $guideModel->all();
        
        // Get currently assigned guide
        $assign = new GuideAssignment();
        $assignedGuide = $assign->getAssignedGuide($schedule_id);

        require "./views/schedules/editForm.php";
    }

    // Lưu sửa
    public function postEdit()
    {
        $schedule_id = $_POST['schedule_id'];

        $data = [
            'tour_id'       => $_POST['tour_id'],
            'depart_date'   => $_POST['depart_date'],
            'return_date'   => $_POST['return_date'] ?? null,
            'meeting_point' => $_POST['meeting_point'],
            'seats_total'   => $_POST['seats_total'],
            'seats_booked'  => $_POST['seats_booked'],
            'status'        => $_POST['status']
        ];

        $model = new Schedule();
        $model->update($schedule_id, $data);
        
        // Handle guide assignment/unassignment
        if (isset($_POST['guide_id'])) {
            $guide_id = $_POST['guide_id'];
            $assign = new GuideAssignment();
            
            if (!empty($guide_id)) {
                // Assign new guide
                $assign->assignGuide($schedule_id, $guide_id);
            } else {
                // Unassign current guide if exists
                $assigned = $assign->getAssignedGuide($schedule_id);
                if ($assigned) {
                    // Get the assignment ID to remove it
                    // Since getAssignedGuide returns the assignment details, we need to get the assignment_id
                    // Let's modify our approach to get all assignments for this schedule
                    $assignments = $assign->getBySchedule($schedule_id);
                    if (!empty($assignments)) {
                        foreach ($assignments as $assignment) {
                            $assign->remove($assignment['assignment_id']);
                        }
                    }
                }
            }
        }

        header("Location: ?route=/schedules");
    }

    // Xóa schedule (xóa thật sự khỏi database)
    public function delete()
    {
        $schedule_id = $_GET['schedule_id'];

        $model = new Schedule();
        $model->delete($schedule_id);

        header("Location: ?route=/schedules");
    }


    // ==========================
    // 2 ĐIỀU HÀNH TOUR
    // ==========================

    // Tour operation dashboard
    public function operationDashboard()
    {
        $model = new Schedule();
        $runningSchedules = $model->getRunningSchedules();
        
        require "./views/schedules/operationDashboard.php";
    }

    // Xem chi tiết lịch trình + HDV + nhật ký
    public function detail()
    {
        $schedule_id = $_GET['schedule_id'];

        // schedule
        $scheduleModel = new Schedule();
        $schedule = $scheduleModel->getById($schedule_id);

        // HDV đã phân công
        $assign = new GuideAssignment();
        $assignedGuide = $assign->getAssignedGuide($schedule_id);

        // nhật ký
        $log = new DailyLog();
        $logs = $log->getLogsBySchedule($schedule_id);
        
        // Check if logs are approved
        $logsApproved = $scheduleModel->areLogsApproved($schedule_id);

        require "./views/schedules/detail.php";
    }


    // ==========================
    // PHÂN CÔNG HƯỚNG DẪN VIÊN
    // ==========================

    public function assignGuideForm()
    {
        $schedule_id = $_GET['schedule_id'];

        $guideModel = new Guide();
        $guides = $guideModel->all();

        $assign = new GuideAssignment();
        $assigned = $assign->getAssignedGuide($schedule_id);
        
        // Get guides with assignment status
        $guidesWithStatus = $assign->getGuidesWithAssignmentStatus($schedule_id);

        require "./views/schedules/assignGuide.php";
    }

    public function postAssignGuide()
    {
        $schedule_id = $_POST['schedule_id'];
        $guide_id    = $_POST['guide_id'];

        $assign = new GuideAssignment();
        $assign->assignGuide($schedule_id, $guide_id);

        header("Location: ?route=/schedules/detail&schedule_id=" . $schedule_id);
    }

    // Hủy phân công HDV
    public function removeGuide()
    {
        $id = $_GET['id'];
        $schedule_id = $_GET['schedule_id'];

        $assign = new GuideAssignment();
        $assign->remove($id);

        // Redirect back to the schedule listing instead of detail page
        header("Location: ?route=/schedules");
    }

    // Assign guide directly from schedule listing
    public function assignGuideFromList()
    {
        $schedule_id = $_GET['schedule_id'];
        $guide_id = $_GET['guide_id'];

        $assign = new GuideAssignment();
        $assign->assignGuide($schedule_id, $guide_id);

        // Redirect back to the schedule listing
        header("Location: ?route=/schedules");
    }


    // ==========================
    // NHẬT KÝ HẰNG NGÀY
    // ==========================

    public function dailyLog()
    {
        $schedule_id = $_GET['schedule_id'];

        $log = new DailyLog();
        $logs = $log->getLogsBySchedule($schedule_id);
        
        // Get grouped logs by date
        $groupedLogs = $log->getLogsGroupedByDate($schedule_id);

        require "./views/schedules/dailyLog.php";
    }

    public function addDailyLog()
    {
        $schedule_id = $_POST['schedule_id'];
        $guide_id    = $_POST['guide_id'];
        $content     = $_POST['content'];

        $log = new DailyLog();
        $log->addLog($guide_id, $schedule_id, $content);

        header("Location: ?route=/schedules/dailyLog&schedule_id=" . $schedule_id);
    }
    
    // Delete a daily log
    public function deleteDailyLog()
    {
        $log_id = $_GET['log_id'];
        $schedule_id = $_GET['schedule_id'];
        
        $log = new DailyLog();
        $log->deleteLog($log_id);
        
        header("Location: ?route=/schedules/dailyLog&schedule_id=" . $schedule_id);
    }


    // ==========================
    // XÁC NHẬN KẾT THÚC TOUR
    // ==========================

    public function confirmFinish()
    {
        $schedule_id = $_GET['schedule_id'];

        $model = new Schedule();
        $model->approveLogs($schedule_id);

        header("Location: ?route=/schedules/detail&schedule_id=" . $schedule_id);
    }
    
    // ==========================
    // CẬP NHẬT SỐ GHẾ
    // ==========================
    
    public function updateSeats()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $schedule_id = $_POST['schedule_id'];
            $seats_total = $_POST['seats_total'];
            
            $model = new Schedule();
            $model->updateSeats($schedule_id, $seats_total);
            
            header("Location: ?route=/schedules");
            exit();
        }
    }
}