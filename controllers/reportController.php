<?php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../models/reportModel.php";

class ReportController {

    public function index() {
        $database = new Database();
        $db = $database->connect();

        $report = new Report($db);

        if (isset($_GET['action']) && isset($_GET['id'])) {
            $report_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

            if ($report_id && $_GET['action'] === 'resolve') {
                $report->resolveReport($report_id);
            }

            header("Location: /PUTRARIDE/index.php?page=reports");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (isset($_POST['form_action']) && $_POST['form_action'] === 'add') {
                $report->addReport($_POST);
            }

            if (isset($_POST['form_action']) && $_POST['form_action'] === 'edit') {
                $report->updateReport($_POST);
            }

            header("Location: /PUTRARIDE/index.php?page=reports");
            exit();
        }

        $reports = $report->getAllReports();

        require __DIR__ . "/../views/admin/reportList.php";
    }
}