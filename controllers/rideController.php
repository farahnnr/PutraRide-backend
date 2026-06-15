<?php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../models/rideModel.php";

class RideController {

    public function index() {
        $database = new Database();
        $db = $database->connect();

        $ride = new Ride($db);

        if (isset($_GET['action']) && isset($_GET['id'])) {
            $ride_id = $_GET['id'];

            if ($_GET['action'] === 'cancel') {
                $ride->cancelRide($ride_id);
            }

            header("Location: /PUTRARIDE/index.php?page=rides");
            exit();
        }

        $rides = $ride->getAllRides();

        require __DIR__ . "/../views/admin/rideList.php";
    }
}