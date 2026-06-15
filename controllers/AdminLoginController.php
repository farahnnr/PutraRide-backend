<?php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../models/AdminLoginModel.php";

class AdminLoginController {

    public function index() {
        $error = "";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $database = new Database();
            $db = $database->connect();

            $adminModel = new AdminLogin($db);

            $email = $_POST["email"];
            $password = $_POST["password"];

            $admin = $adminModel->login($email);

            if ($admin && $password === $admin["password"]) {

                $_SESSION["admin_id"] = $admin["admin_id"];
                $_SESSION["admin_name"] = $admin["full_name"];
                $_SESSION["admin_email"] = $admin["email"];

                header("Location: /PUTRARIDE/index.php");
                exit();

            } else {
                $error = "Invalid email or password.";
            }
        }

        require __DIR__ . "/../views/admin/adminlogin.php";
    }

    public function logout() {
        session_destroy();

        header("Location: /PUTRARIDE/index.php?page=adminlogin");
        exit();
    }
}