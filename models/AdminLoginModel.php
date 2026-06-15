<?php

class AdminLogin {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login($email) {
        $query = "SELECT * FROM admins WHERE email = :email LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}