<?php

namespace App\User\Registration;

use App\Config\Database\ConfigDatabase;
use App\Config\Database\DatabaseConnection;
use PDO;
use PDOException;

class Login
{
    public string $email;
    public string $password;
}

class CheckLogin
{
    public DatabaseConnection $connection;
    public ConfigDatabase $table;

    public function __construct()
    {
        $this->connection = new DatabaseConnection();
        $this->table = new ConfigDatabase();
    }

    public function validateUserLogin(string $email, string $password): bool
    {
        $statement = $this->connection->getConnection()->prepare(
            "SELECT id, email, password
            FROM {$this->table->getTable('users')}
            WHERE email = :email"
        );
    
        $statement->bindParam(':email', $email, PDO::PARAM_STR);
        $statement->execute();
    
        $result = $statement->fetch(PDO::FETCH_ASSOC);
    
        if (!$result) {
            return false;
        }

        if ($password === $result['password']) {
            $token = bin2hex(random_bytes(64));
    
            $updateStmt = $this->connection->getConnection()->prepare(
                "UPDATE {$this->table->getTable('users')}
                SET token = :token,
                    last_co = NOW()
                WHERE email = :email"
            );
    
            $updateStmt->bindParam(':token', $token, PDO::PARAM_STR);
            $updateStmt->bindParam(':email', $email, PDO::PARAM_STR);
            $updateStmt->execute();
    
            $_SESSION['user_id'] = $result['id'];
            $_SESSION['email'] = $result['email'];
            $_SESSION['token'] = $token;
    
            $cookieOptions = [
                'expires' => time() + 3600,
                'secure' => true,
                'httponly' => true,
                'path' => '/',
                'samesite' => 'Lax',
            ];
    
            setcookie('user_id', $result['id'], $cookieOptions);
            setcookie('email', $result['email'], $cookieOptions);
            setcookie('token', $token, $cookieOptions);
    
            return true;
        }
    
        return false;
    }

    public function getUserPassword(int $id): string
    {
        $stmt = $this->connection->getConnection()->prepare(
            "SELECT password
            FROM {$this->table->getTable('users')}
            WHERE id = :id"
        );
    
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result['password'];
    }    
}