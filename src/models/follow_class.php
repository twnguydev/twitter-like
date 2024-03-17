<?php

namespace App\Feature\Follow;

use App\Config\Database\ConfigDatabase;
use App\Config\Database\DatabaseConnection;
use PDOException;
use PDO;

class Follow
{
    public int $id;
    public int $id_user;
    public int $id_followed;
    public string $date;
    public int $count_followers;
    public int $count_followings;
}

class FollowRepository
{
    public DatabaseConnection $connection;
    public ConfigDatabase $table;

    public function __construct()
    {
        $this->connection = new DatabaseConnection();
        $this->table = new ConfigDatabase();
    }

    public function setFollow(int $id_user, int $id_followed): bool
    {
        $stmt = $this->connection->getConnection()->prepare(
            "INSERT INTO {$this->table->getTable('user_follow')} (id_follower, id_target, date)
            VALUES (:id_user, :id_followed, NOW())"
        );

        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->bindParam(':id_followed', $id_followed, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    public function setUnfollow(int $id_user, int $id_followed): bool
    {
        $stmt = $this->connection->getConnection()->prepare(
            "DELETE FROM {$this->table->getTable('user_follow')}
            WHERE id_follower = :id_user
                AND id_target = :id_followed"
        );

        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->bindParam(':id_followed', $id_followed, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    public function isFollowing(int $id_user, int $id_followed): bool
    {
        $stmt = $this->connection->getConnection()->prepare(
            "SELECT id
            FROM {$this->table->getTable('user_follow')}
            WHERE id_follower = :id_user
                AND id_target = :id_followed"
        );

        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->bindParam(':id_followed', $id_followed, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function getFollowers(int $id_user): array
    {
        $stmt = $this->connection->getConnection()->prepare(
            "SELECT id,
                    id_follower,
                    id_target,
                    date,
                    COUNT(id_follower) AS followers
            FROM {$this->table->getTable('user_follow')}
            WHERE id_target = :id_user
            GROUP BY id"
        );

        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->execute();

        $followers = [];

        while ($result = $stmt->fetchObject(Follow::class)) {
            $followers[] = $result;
        }

        return $followers;
    }

    public function getFollowings(int $id_user): array
    {
        $stmt = $this->connection->getConnection()->prepare(
            "SELECT id,
                    id_follower,
                    id_target,
                    date
            FROM {$this->table->getTable('user_follow')}
            WHERE id_follower = :id_user"
        );

        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->execute();

        $followings = [];

        while($result = $stmt->fetch()) {
            $follow = new Follow();
            $follow->id = $result['id'];
            $follow->id_user = $result['id_follower'];
            $follow->id_followed = $result['id_target'];
            $follow->date = $result['date'];

            $followings[] = $follow;
        }

        return $followings;
    }

    public function countAllFollowers(int $id_user): int
    {
        $stmt = $this->connection->getConnection()->prepare(
            "SELECT COUNT(id_follower) AS followers
            FROM {$this->table->getTable('user_follow')}
            WHERE id_target = :id_user"
        );

        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch();

        return $result['followers'];
    }

    public function countAllFollowings(int $id_user): int
    {
        $stmt = $this->connection->getConnection()->prepare(
            "SELECT COUNT(id_target) AS followings
            FROM {$this->table->getTable('user_follow')}
            WHERE id_follower = :id_user"
        );

        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch();

        return $result['followings'];
    }
}
