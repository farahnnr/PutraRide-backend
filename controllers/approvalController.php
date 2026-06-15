<?php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../models/approvalModel.php";

class ApprovalController {

    public function index() {
        $database = new Database();
        $db = $database->connect();

        $approval = new Approval($db);

        if (isset($_GET['action']) && isset($_GET['id'])) {

            $driver_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            $action = $_GET['action'];

            if ($driver_id) {

                if ($action === 'approve') {
                    $approval->approveDriver($driver_id);
                }

                if ($action === 'reject') {
                    $approval->rejectDriver($driver_id);
                }
            }

            header("Location: index.php?page=approval");
            exit();
        }

        $pendingDrivers = $approval->getPendingDrivers();

        require __DIR__ . "/../views/admin/approval.php";
    }
}