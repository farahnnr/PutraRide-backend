<?php

class Ride {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllRides() {
        $query = "SELECT 
                    r.ride_id,
                    p.full_name AS passenger_name,
                    d.full_name AS driver_name,
                    d.vehicle_type,
                    r.pickup_location,
                    r.destination,
                    r.fare,
                    r.ride_status
                  FROM rides r
                  JOIN passengers p ON r.passenger_id = p.passenger_id
                  JOIN drivers d ON r.driver_id = d.driver_id
                  ORDER BY r.ride_id ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cancelRide($ride_id) {
        $query = "UPDATE rides 
                  SET ride_status = 'CANCELLED'
                  WHERE ride_id = :ride_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":ride_id", $ride_id);

        return $stmt->execute();
    }
}