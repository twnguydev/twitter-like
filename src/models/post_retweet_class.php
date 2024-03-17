<?php

namespace App\Feature\Post\Retweet;

use App\Config\Database\ConfigDatabase;
use App\Config\Database\DatabaseConnection;
use PDO;
use PDOException;

class Retweet
{
    public int $id;
    public int $id_user;
    public int $id_post;
    public string $date;
    public int $count_retweets;
}

class RetweetRepository
{
    public DatabaseConnection $connection;
    public ConfigDatabase $table;

    public function __construct()
    {
        $this->connection = new DatabaseConnection();
        $this->table = new ConfigDatabase();
    }

    public function setRetweet(int $id_user, int $id_post): bool
    {
        $stmt = $this->connection->getConnection()->prepare(
            "INSERT INTO {$this->table->getTable('post_retweets')} (id_user, id_post, date)
            VALUES (:id_user, :id_post, NOW())"
        );

        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->bindParam(':id_post', $id_post, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    public function unsetRetweet(int $id_user, int $id_post): bool
    {
        $stmt = $this->connection->getConnection()->prepare(
            "DELETE FROM {$this->table->getTable('post_retweets')}
            WHERE id_user = :id_user
                AND id_post = :id_post"
        );

        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->bindParam(':id_post', $id_post, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    public function isRetweeted(int $id_user, int $id_post): bool
    {
        $stmt = $this->connection->getConnection()->prepare(
            "SELECT COUNT(*) AS count
            FROM {$this->table->getTable('post_retweets')}
            WHERE id_user = :id_user
                AND id_post = :id_post"
        );

        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->bindParam(':id_post', $id_post, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->count > 0;
    }

    public function countRetweets(int $id_post): int
    {
        $stmt = $this->connection->getConnection()->prepare(
            "SELECT COUNT(*) AS count
            FROM {$this->table->getTable('post_retweets')}
            WHERE id_post = :id_post"
        );

        $stmt->bindParam(':id_post', $id_post, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch();

        return $result['count'];
    }
}