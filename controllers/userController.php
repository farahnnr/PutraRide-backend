<?php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../models/userModel.php";

class UserController {

    public function index() {
        $database = new Database();
        $db = $database->connect();

        $user = new User($db);

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (isset($_POST["action"]) && $_POST["action"] === "update") {
                $user->updateUser($_POST);

                header("Location: /PUTRARIDE/index.php?page=users");
                exit();
            }
        }

        if (isset($_GET["action"]) && isset($_GET["id"])) {
            $user_id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

            if ($user_id && $_GET["action"] === "suspend") {
                $user->suspendUser($user_id);
            }

            header("Location: /PUTRARIDE/index.php?page=users");
            exit();
        }

        $users = $user->getAllUsers();

        require __DIR__ . "/../views/admin/manageUsers.php";
    }

    public function addUser() {
        $database = new Database();
        $db = $database->connect();

        $user = new User($db);

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $user->addUser($_POST, $_FILES);

            header("Location: /PUTRARIDE/index.php?page=users");
            exit();
        }

        require __DIR__ . "/../views/admin/addUser.php";
    }
}