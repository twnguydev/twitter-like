<?php

namespace App\User\Registration;

use App\Config\Database\DatabaseConnection;
use App\Config\Database\ConfigDatabase;
use PDO;
use PDOException;

class Signup
{
    public string $firstname;
    public string $lastname;
    public string $email;
    public string $birthdate;
    public string $password;
    public array $hobbies;
}

class CheckSignup
{
    public DatabaseConnection $connection;
    public ConfigDatabase $table;

    public function __construct()
    {
        $this->connection = new DatabaseConnection();
        $this->table = new ConfigDatabase;
    }

    public function setUserRegistration(
        int $genre_id,
        string $fullname,
        string $pseudo,
        string $email,
        string $password,
        string $birthdate,
        string $city
    ): bool {
        $userStmt = $this->connection->getConnection()->prepare(
            "INSERT INTO {$this->table->getTable('users')} (id_genre, pseudo, fullname, email, password, token, birthdate, city, last_co, active)
            VALUES (:id_genre, :pseudo, :fullname, :email, :password, NULL, :birthdate, :city, NULL, 1)"
        );

        $userStmt->bindParam(':id_genre', $genre_id, PDO::PARAM_INT);
        $userStmt->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
        $userStmt->bindParam(':fullname', $fullname, PDO::PARAM_STR);
        $userStmt->bindParam(':email', $email, PDO::PARAM_STR);
        $userStmt->bindParam(':password', $password, PDO::PARAM_STR);
        $userStmt->bindParam(':birthdate', $birthdate, PDO::PARAM_STR);
        $userStmt->bindParam(':city', $city, PDO::PARAM_STR);

        if ($userStmt->execute()) {
            $lastUserId = $this->connection->getConnection()->lastInsertId();

            $profileStmt = $this->connection->getConnection()->prepare(
                "INSERT INTO {$this->table->getTable('profiles')} (id_user, username)
                    VALUES (:id_user, :username)"
            );

            $profileStmt->bindParam(':id_user', $lastUserId, PDO::PARAM_INT);
            $profileStmt->bindParam(':username', $fullname, PDO::PARAM_STR);

            $profileStmt->execute();

            return true;
        }

        return false;
    }
}
