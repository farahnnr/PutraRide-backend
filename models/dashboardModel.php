<?php

class Dashboard {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function countData($table) {
        $allowedTables = ["users", "drivers", "rides", "reports", "passengers"];

        if (!in_array($table, $allowedTables)) {
            return 0;
        }

        $query = "SELECT COUNT(*) AS total FROM $table";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function countPendingDrivers() {
        $query = "SELECT COUNT(*) AS total FROM drivers WHERE status = 'PENDING'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function getDriverVehicleGenderData() {
    $query = "SELECT vehicle_type, gender, COUNT(*) AS total
              FROM drivers
              GROUP BY vehicle_type, gender";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getRidesPerDate() {
    $query = "SELECT DATE(start_time) AS ride_date, COUNT(*) AS total
              FROM rides
              GROUP BY DATE(start_time)
              ORDER BY ride_date ASC";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}