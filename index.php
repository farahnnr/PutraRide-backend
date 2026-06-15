<?php

session_start();

$page = $_GET['page'] ?? 'dashboard';

if ($page === 'adminlogin') {
    require_once __DIR__ . "/controllers/AdminLoginController.php";
    $controller = new AdminLoginController();
    $controller->index();
    exit();
}

if ($page === 'logout') {
    require_once __DIR__ . "/controllers/AdminLoginController.php";
    $controller = new AdminLoginController();
    $controller->logout();
    exit();
}

if (!isset($_SESSION['admin_id'])) {
    header("Location: /PUTRARIDE/index.php?page=adminlogin");
    exit();
}

if ($page === 'approval') {
    require_once __DIR__ . "/controllers/approvalController.php";
    $controller = new ApprovalController();
    $controller->index();

} elseif ($page === 'users') {
    require_once __DIR__ . "/controllers/userController.php";
    $controller = new UserController();
    $controller->index();

} elseif ($page === 'addUser') {
    require_once __DIR__ . "/controllers/userController.php";
    $controller = new UserController();
    $controller->addUser();

} elseif ($page === 'rides') {
    require_once __DIR__ . "/controllers/rideController.php";
    $controller = new RideController();
    $controller->index();

} elseif ($page === 'reports') {
    require_once __DIR__ . "/controllers/reportController.php";
    $controller = new ReportController();
    $controller->index();

} else {
    require_once __DIR__ . "/controllers/dashboardController.php";
    $controller = new DashboardController();
    $controller->index();
}

?>