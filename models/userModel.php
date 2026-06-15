
<?php

class User {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllUsers() {

        $query = "
            SELECT
                u.*,
                d.vehicle_type,
                d.plate_number
            FROM users u
            LEFT JOIN drivers d
                ON u.user_id = d.user_id
            ORDER BY u.user_id ASC
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function suspendUser($user_id) {

        $query = "
            UPDATE users
            SET status = 'SUSPENDED'
            WHERE user_id = :user_id
        ";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":user_id", $user_id);

        return $stmt->execute();
    }

    public function updateUser($data) {

        $query = "
            UPDATE users
            SET
                full_name = :full_name,
                email = :email,
                phone_number = :phone_number,
                role = :role,
                gender = :gender,
                status = :status
            WHERE user_id = :user_id
        ";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ":full_name" => $data["full_name"],
            ":email" => $data["email"],
            ":phone_number" => $data["phone_number"],
            ":role" => $data["role"],
            ":gender" => $data["gender"],
            ":status" => $data["status"],
            ":user_id" => $data["user_id"]
        ]);
    }

    public function addUser($data, $files = []) {

        $passwordHash = password_hash(
            $data["password"],
            PASSWORD_DEFAULT
        );

        $query = "
            INSERT INTO users
            (
                full_name,
                email,
                phone_number,
                password,
                role,
                gender,
                status
            )
            VALUES
            (
                :full_name,
                :email,
                :phone_number,
                :password,
                :role,
                :gender,
                'ACTIVE'
            )
        ";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            ":full_name" => $data["full_name"],
            ":email" => $data["email"],
            ":phone_number" => $data["phone_number"],
            ":password" => $passwordHash,
            ":role" => $data["role"],
            ":gender" => $data["gender"]
        ]);

        $user_id = $this->conn->lastInsertId();

        if ($data["role"] === "DRIVER") {

            $icImage = "";
            $licenseImage = "";

            if (
                isset($files["ic_image"]) &&
                $files["ic_image"]["error"] === 0
            ) {

                $icImage =
                    "uploads/" .
                    time() .
                    "_" .
                    basename(
                        $files["ic_image"]["name"]
                    );

                move_uploaded_file(
                    $files["ic_image"]["tmp_name"],
                    $icImage
                );
            }

            if (
                isset($files["license_image"]) &&
                $files["license_image"]["error"] === 0
            ) {

                $licenseImage =
                    "uploads/" .
                    time() .
                    "_" .
                    basename(
                        $files["license_image"]["name"]
                    );

                move_uploaded_file(
                    $files["license_image"]["tmp_name"],
                    $licenseImage
                );
            }

            $driverQuery = "
                INSERT INTO drivers
                (
                    user_id,
                    vehicle_type,
                    plate_number,
                    ic_image,
                    license_image,
                    status,
                    submitted_at
                )
                VALUES
                (
                    :user_id,
                    :vehicle_type,
                    :plate_number,
                    :ic_image,
                    :license_image,
                    'ACTIVE',
                    NOW()
                )
            ";

            $driverStmt =
                $this->conn->prepare($driverQuery);

            $driverStmt->execute([
                ":user_id" => $user_id,
                ":vehicle_type" => $data["vehicle_type"],
                ":plate_number" => $data["plate_number"],
                ":ic_image" => $icImage,
                ":license_image" => $licenseImage
            ]);
        }

        return true;
    }

    public function getUserById($user_id) {

        $query = "
            SELECT *
            FROM users
            WHERE user_id = :user_id
        ";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":user_id", $user_id);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

