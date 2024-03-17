<?php

namespace App\User;

use App\Config\Database\DatabaseConnection;
use App\Config\Database\ConfigDatabase;
use PDOException;
use PDO;

class User
{
    public int $id;
    public string $pseudo;
    public string $birthdate;
    public string $fullname;
    public string $username;
    public string $biography;
    public string $city;
    public string $profile_path;
    public string $banner_path;
    public string $token;
    public string $last_co;
    public string $genre;
    public string $email;
    public int $count_followers;
}

class UserRepository
{
    public DatabaseConnection $connection;
    public ConfigDatabase $table;

    public function __construct()
    {
        $this->connection = new DatabaseConnection();
        $this->table = new ConfigDatabase();
    }

    public function getUser(?int $id_user = null, ?string $pseudo = null, ?string $email = null): ?User
    {
        $stmt = $this->connection->getConnection()->prepare(
            "SELECT u.id AS user_id,
                    u.pseudo AS user_pseudo,
                    u.email AS user_email,
                    u.fullname AS user_fullname,
                    u.birthdate AS user_birthdate,
                    u.city AS user_city,
                    p.username AS user_username,
                    p.biography AS user_biography,
                    p.banner_path AS user_banner,
                    p.profile_path AS user_profile,
                    g.name AS user_genre,
                    u.last_co AS user_last_co
                FROM {$this->table->getTable('users')} u
                LEFT JOIN {$this->table->getTable('profiles')} p ON u.id = p.id_user
                LEFT JOIN {$this->table->getTable('genres')} g ON u.id_genre = g.id
                WHERE (u.id = :id OR
                    u.pseudo = :pseudo OR
                    u.email = :email) AND
                    u.active = 1"
        );

        $stmt->bindParam(':id', $id_user, PDO::PARAM_INT);
        $stmt->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        $stmt->execute();
        $result = $stmt->fetch();

        if (!$result) {
            return null;
        }

        $user = new User();
        $user->id = $result['user_id'];
        $user->fullname = $result['user_fullname'];
        $user->email = $result['user_email'];
        $user->pseudo = $result['user_pseudo'];
        $user->birthdate = $result['user_birthdate'];
        $user->username = $result['user_username'];
        $user->genre = $result['user_genre'];
        $user->biography = $result['user_biography'];
        $user->city = $result['user_city'];
        $user->last_co = $result['user_last_co'] ?? null;
        $user->profile_path = $result['user_profile'];
        $user->banner_path = $result['user_banner'];
    
        return $user;
    }

    public function getUsers(int $limit = null): array
    {
        $query = "SELECT u.id AS user_id,
                    u.pseudo AS user_pseudo,
                    u.email AS user_email,
                    u.fullname AS user_fullname,
                    u.birthdate AS user_birthdate,
                    u.city AS user_city,
                    p.username AS user_username,
                    p.biography AS user_biography,
                    p.banner_path AS user_banner,
                    p.profile_path AS user_profile,
                    g.name AS user_genre,
                    u.last_co AS user_last_co
                FROM {$this->table->getTable('users')} u
                LEFT JOIN {$this->table->getTable('profiles')} p ON u.id = p.id_user
                LEFT JOIN {$this->table->getTable('genres')} g ON u.id_genre = g.id
                WHERE u.active = 1
                ORDER BY u.id DESC";

        if ($limit !== null) {
            $query .= " LIMIT :limit";
        }

        $stmt = $this->connection->getConnection()->prepare($query);

        if ($limit !== null) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        }

        $stmt->execute();

        $users = [];

        while ($result = $stmt->fetch()) {
            $user = new User();
            $user->id = $result['user_id'];
            $user->fullname = $result['user_fullname'];
            $user->email = $result['user_email'];
            $user->pseudo = $result['user_pseudo'];
            $user->birthdate = $result['user_birthdate'];
            $user->username = $result['user_username'];
            $user->genre = $result['user_genre'];
            $user->biography = $result['user_biography'];
            $user->city = $result['user_city'];
            $user->last_co = $result['user_last_co'] ?? "NULL";
            $user->profile_path = $result['user_profile'];
            $user->banner_path = $result['user_banner'];

            $users[] = $user;
        }

        return $users;
    }

    public function setProfileUpdate(
        int $id_user,
        ?string $biography,
        ?string $username,
        ?string $pseudo,
        ?string $email,
        ?string $city,
        ?string $password
    ): bool {
        $update = $this->connection->getConnection()->prepare(
            "UPDATE {$this->table->getTable('users')} u
            JOIN {$this->table->getTable('profiles')} p ON p.id_user = u.id
            SET u.pseudo = COALESCE(:pseudo, u.pseudo),
                u.city = COALESCE(:city, u.city),
                u.email = COALESCE(:email, u.email),
                u.password = COALESCE(:password, u.password),
                p.username = COALESCE(:username, p.username),
                p.biography = COALESCE(:biography, p.biography)
            WHERE u.id = :id"
        );

        $update->bindParam(':id', $id_user, PDO::PARAM_INT);
        $update->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
        $update->bindParam(':city', $city, PDO::PARAM_STR);
        $update->bindParam(':email', $email, PDO::PARAM_STR);
        $update->bindParam(':password', $password, PDO::PARAM_STR);
        $update->bindParam(':username', $username, PDO::PARAM_STR);
        $update->bindParam(':biography', $biography, PDO::PARAM_STR);

        if ($email !== null) {
            setcookie('email', '', time() - 3600, '/', '', true, true);

            $cookieOptions = [
                'expires' => time() + 3600,
                'secure' => true,
                'httponly' => true,
                'path' => '/',
                'samesite' => 'Lax',
            ];

            setcookie('email', $email, $cookieOptions);
        }

        return $update->execute();
    }

    public function setProfilePhoto(int $id, ?string $image_path, ?string $banner_path): bool
    {
        $searchStmt = $this->connection->getConnection()->prepare(
            "SELECT id_user
            FROM {$this->table->getTable('profiles')}
            WHERE id_user = :id"
        );

        $searchStmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($searchStmt->execute()) {
            $stmt = $this->connection->getConnection()->prepare(
                "UPDATE {$this->table->getTable('profiles')}
                SET profile_path = COALESCE(:profile_path, profile_path),
                    banner_path = COALESCE(:banner_path, banner_path)
                WHERE id_user = :id"
            );

            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':profile_path', $image_path, PDO::PARAM_STR);
            $stmt->bindParam(':banner_path', $banner_path, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return null;
    }

    public function isUserLogged(): ?User
    {
        if (isset($_COOKIE['email'], $_COOKIE['user_id'], $_COOKIE['token'])) {
            $statement = $this->connection->getConnection()->prepare(
                "SELECT u.id AS user_id,
                        u.fullname AS user_fullname,
                        u.pseudo AS user_pseudo,
                        u.email AS user_email,
                        u.city AS user_city,
                        u.token AS user_token,
                        DATE_FORMAT(u.birthdate, '%d/%m/%Y') AS user_birthdate,
                        DATE_FORMAT(u.last_co, '%d/%m/%Y') AS user_last_co,
                        g.name AS user_genre
                FROM {$this->table->getTable('users')} u
                LEFT JOIN {$this->table->getTable('genres')} g ON u.id_genre = g.id
                WHERE u.id = :id
                    AND u.email = :email
                    AND u.token = :token
                GROUP BY u.id"
            );

            $statement->bindParam(':id', $_COOKIE['user_id'], PDO::PARAM_INT);
            $statement->bindParam(':email', $_COOKIE['email'], PDO::PARAM_STR);
            $statement->bindParam(':token', $_COOKIE['token'], PDO::PARAM_STR);
            $statement->execute();

            $result = $statement->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $user = new User();
                $user->id = $result['user_id'];
                $user->pseudo = $result['user_pseudo'];
                $user->fullname = $result['user_fullname'];
                $user->email = $result['user_email'];
                $user->birthdate = $result['user_birthdate'];
                $user->city = $result['user_city'];
                $user->token = $result['user_token'];
                $user->last_co = $result['user_last_co'];
                $user->genre = $result['user_genre'];

                return $user;
            }

            return null;
        }

        return null;
    }

    public function disconnectUser(): bool
    {
        if ($this->isUserLogged()) {
            $statement = $this->connection->getConnection()->prepare(
                "UPDATE {$this->table->getTable('users')}
                SET token = ''
                WHERE id = :user_id
                    AND email = :email"
            );

            $statement->bindParam(':user_id', $this->isUserLogged()->id, PDO::PARAM_INT);
            $statement->bindParam(':email', $this->isUserLogged()->email, PDO::PARAM_STR);
            $statement->execute();

            setcookie('user_id', '', time() - 3600, '/', '', true, true);
            setcookie('email', '', time() - 3600, '/', '', true, true);
            setcookie('token', '', time() - 3600, '/', '', true, true);

            return true;
        }

        return false;
    }

    public function searchUsers(string $query, int $limit = 4): array
    {
        $query = "%$query%";
        $stmt = $this->connection->getConnection()->prepare(
            "SELECT u.id,
                    u.pseudo,
                    p.profile_path,
                    COUNT(uf.id_follower) AS followers
            FROM {$this->table->getTable('users')} u
            JOIN {$this->table->getTable('profiles')} p ON u.id = p.id_user
            LEFT JOIN {$this->table->getTable('user_follow')} uf ON u.id = uf.id_follower
            WHERE u.active = 1 AND
                (u.pseudo LIKE :query)
            GROUP BY u.id, p.profile_path
            ORDER BY followers DESC
            LIMIT :limit"
        );
    
        $stmt->bindParam(':query', $query, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
    
        $users = [];
    
        while ($result = $stmt->fetch()) {
            $user = new User();
            $user->id = $result['id'];
            $user->pseudo = $result['pseudo'];
            $user->profile_path = $result['profile_path'];
            $user->count_followers = $result['followers'];
    
            $users[] = $user;
        }
    
        return $users;
    }
}
