<?php

class Report {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllReports() {
        $query = "SELECT * FROM reports ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addReport($data) {
        $query = "INSERT INTO reports
                  (ride_id, passenger_id, driver_id, report_type, report_source, report_message, report_status, created_at)
                  VALUES
                  (:ride_id, :passenger_id, :driver_id, :report_type, :report_source, :report_message, 'PENDING', NOW())";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":ride_id", $data['ride_id']);
        $stmt->bindParam(":passenger_id", $data['passenger_id']);
        $stmt->bindParam(":driver_id", $data['driver_id']);
        $stmt->bindParam(":report_type", $data['report_type']);
        $stmt->bindParam(":report_source", $data['report_source']);
        $stmt->bindParam(":report_message", $data['report_message']);

        return $stmt->execute();
    }

    public function updateReport($data) {
        $query = "UPDATE reports
                  SET ride_id = :ride_id,
                      passenger_id = :passenger_id,
                      driver_id = :driver_id,
                      report_type = :report_type,
                      report_source = :report_source,
                      report_message = :report_message,
                      report_status = :report_status
                  WHERE report_id = :report_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":report_id", $data['report_id']);
        $stmt->bindParam(":ride_id", $data['ride_id']);
        $stmt->bindParam(":passenger_id", $data['passenger_id']);
        $stmt->bindParam(":driver_id", $data['driver_id']);
        $stmt->bindParam(":report_type", $data['report_type']);
        $stmt->bindParam(":report_source", $data['report_source']);
        $stmt->bindParam(":report_message", $data['report_message']);
        $stmt->bindParam(":report_status", $data['report_status']);

        return $stmt->execute();
    }

    public function resolveReport($report_id) {
        $query = "UPDATE reports
                  SET report_status = 'RESOLVED'
                  WHERE report_id = :report_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":report_id", $report_id);

        return $stmt->execute();
    }
}