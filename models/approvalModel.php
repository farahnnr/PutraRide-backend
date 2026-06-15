<?php

class Approval {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getPendingDrivers() {
        $query = "SELECT * FROM drivers 
                  WHERE status = 'PENDING' 
                  ORDER BY submitted_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function approveDriver($driver_id) {
        try {
            $this->conn->beginTransaction();

            $query = "UPDATE drivers 
                      SET status = 'ACTIVE',
                          approved_at = NOW(),
                          rejected_at = NULL
                      WHERE driver_id = :driver_id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":driver_id", $driver_id);
            $stmt->execute();

            $query = "UPDATE users 
                      SET status = 'ACTIVE'
                      WHERE user_id = (
                          SELECT user_id 
                          FROM drivers 
                          WHERE driver_id = :driver_id
                      )";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":driver_id", $driver_id);
            $stmt->execute();

            $this->conn->commit();
            return true;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function rejectDriver($driver_id) {
        try {
            $this->conn->beginTransaction();

            $query = "UPDATE drivers 
                      SET status = 'REJECTED',
                          rejected_at = NOW(),
                          approved_at = NULL
                      WHERE driver_id = :driver_id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":driver_id", $driver_id);
            $stmt->execute();

            $query = "UPDATE users 
                      SET status = 'REJECTED'
                      WHERE user_id = (
                          SELECT user_id 
                          FROM drivers 
                          WHERE driver_id = :driver_id
                      )";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":driver_id", $driver_id);
            $stmt->execute();

            $this->conn->commit();
            return true;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}