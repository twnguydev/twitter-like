<?php

namespace App\Config\Database;

use PDO;
use PDOException;

class ConfigDatabase
{
    protected string $host = "";
    protected string $db_name = "";
    protected string $user = "";
    protected string $password = "";
    protected string $charset = "utf8mb4";

    public function getTable(string $table): ?string
    {
        $tables = $this->getTables();

        if (in_array($table, $tables)) {
            return $table;
        }

        return null;
    }

    protected function getTables(): array
    {
        $statement = $this->getConnection()->query("SHOW TABLES FROM {$this->db_name}");
        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }

    protected function getConnection(): PDO
    {
        $databaseConnection = new DatabaseConnection();
        return $databaseConnection->getConnection();
    }
}

class DatabaseConnection extends ConfigDatabase
{
    public ?PDO $database = null;

    public function getConnection(): PDO
    {
        if ($this->database === null){
            try {
                $this->database = new PDO("mysql:host={$this->host};dbname={$this->db_name};charset={$this->charset}", $this->user, $this->password);
                $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->database->exec("SET NAMES {$this->charset}");
            } catch (PDOException $e) {
                die("Erreur de connexion à la base de données: " . $e->getMessage());
            }
        }
        return $this->database;
    }
}