<?php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../models/dashboardModel.php";

class DashboardController {

    public function index() {
        $database = new Database();
        $db = $database->connect();

        $dashboard = new Dashboard($db);

        $totalDrivers = $dashboard->countData("drivers");
        $totalUsers = $dashboard->countData("users");
        $totalRides = $dashboard->countData("rides");
        $pendingApprovals = $dashboard->countPendingDrivers();
        $driverVehicleGenderData = $dashboard->getDriverVehicleGenderData();
        $ridesPerDateData = $dashboard->getRidesPerDate();

        require __DIR__ . "/../views/admin/dashboard.php";
    }
}