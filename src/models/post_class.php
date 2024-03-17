<?php

namespace App\Feature\Post;

use App\Config\Database\ConfigDatabase;
use App\Config\Database\DatabaseConnection;
use PDOException;
use PDO;

class Post
{
    public int $id;
    public int $id_user;
    public string $author;
    public string $author_photo;
    public string $message;
    public array $images;
    public string $date;
    public string $date_in_minutes;
    public int $count_likes;
    public int $count_retweets;
    public int $count_comments;
    public bool $isLiked;
    public bool $isRetweeted;
}

class PostRepository
{
    public DatabaseConnection $connection;
    public ConfigDatabase $table;

    public function __construct()
    {
        $this->connection = new DatabaseConnection();
        $this->table = new ConfigDatabase();
    }

    public function setPostRegistration(int $id_user, string $message, ?array $hashtags, ?array $uploaded_photos): bool
    {
        $postStmt = $this->connection->getConnection()->prepare(
            "INSERT INTO {$this->table->getTable('posts')} (id_user, post, date)
            VALUES (:id_user, :message, NOW())"
        );

        $postStmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $postStmt->bindParam(':message', $message, PDO::PARAM_STR);
        $postStmt->execute();

        $lastPostId = $this->connection->getConnection()->lastInsertId();

        if ($hashtags !== null && !empty($hashtags)) {
            foreach ($hashtags as $hashtag) {
                $hashtagStmt = $this->connection->getConnection()->prepare(
                    "INSERT INTO {$this->table->getTable('hashtags')} (id_post, name)
                    VALUES (:id_post, :hashtag)"
                );

                $hashtagStmt->bindParam(':id_post', $lastPostId, PDO::PARAM_INT);
                $hashtagStmt->bindParam(':hashtag', $hashtag, PDO::PARAM_STR);
                $result = $hashtagStmt->execute();

                if (!$result) {
                    return false;
                }
            }
        }

        if ($uploaded_photos !== null && !empty($uploaded_photos)) {
            foreach ($uploaded_photos as $photo) {
                $photoStmt = $this->connection->getConnection()->prepare(
                    "INSERT INTO {$this->table->getTable('image_paths')} (id_post, image_path, image_hash)
                            VALUES (:id_post, :image_path, :image_hash)"
                );

                $photoStmt->bindParam(':id_post', $lastPostId, PDO::PARAM_INT);
                $photoStmt->bindParam(':image_path', $photo['file_path'], PDO::PARAM_STR);
                $photoStmt->bindParam(':image_hash', $photo['hash'], PDO::PARAM_STR);

                $result = $photoStmt->execute();

                if (!$result) {
                    return false;
                }
            }
        }

        return true;
    }

    public function getImageFromHash(string $hash): string
    {
        $stmt = $this->connection->getConnection()->prepare(
            "SELECT image_path
            FROM {$this->table->getTable('image_paths')}
            WHERE image_hash = :hash"
        );

        $stmt->bindParam(':hash', $hash, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch();

        return $result['image_path'];
    }

    public function getPost(int $id_post): ?Post
    {
        $stmt = $this->connection->getConnection()->prepare(
            "SELECT p.id AS post_id,
                    p.post AS message,
                    CASE
                        WHEN TIMESTAMPDIFF(SECOND, p.date, NOW()) < 60 THEN CONCAT('il y a ', TIMESTAMPDIFF(SECOND, p.date, NOW()), ' seconde', IF(TIMESTAMPDIFF(SECOND, p.date, NOW()) > 1, 's', ''))
                        WHEN TIMESTAMPDIFF(MINUTE, p.date, NOW()) < 60 THEN CONCAT('il y a ', TIMESTAMPDIFF(MINUTE, p.date, NOW()), ' minute', IF(TIMESTAMPDIFF(MINUTE, p.date, NOW()) > 1, 's', ''))
                        WHEN TIMESTAMPDIFF(HOUR, p.date, NOW()) < 24 THEN CONCAT('il y a ', TIMESTAMPDIFF(HOUR, p.date, NOW()), ' heure', IF(TIMESTAMPDIFF(HOUR, p.date, NOW()) > 1, 's', ''), ' et ', TIMESTAMPDIFF(MINUTE, p.date, NOW()) % 60, ' minute', IF(TIMESTAMPDIFF(MINUTE, p.date, NOW()) % 60 > 1, 's', ''))
                        WHEN TIMESTAMPDIFF(DAY, p.date, NOW()) < 7 THEN CONCAT('il y a ', TIMESTAMPDIFF(DAY, p.date, NOW()), ' jour', IF(TIMESTAMPDIFF(DAY, p.date, NOW()) > 1, 's', ''))
                        WHEN TIMESTAMPDIFF(WEEK, p.date, NOW()) < 4 THEN CONCAT('il y a ', TIMESTAMPDIFF(WEEK, p.date, NOW()), ' semaine', IF(TIMESTAMPDIFF(WEEK, p.date, NOW()) > 1, 's', ''))
                        ELSE DATE_FORMAT(p.date, '%d/%m/%Y')
                    END AS time_difference,
                    COUNT(pl.id_post) AS nb_likes,
                    COUNT(pr.id_post) AS nb_retweets,
                    COUNT(pc.id) AS nb_comments,
                    u.pseudo AS author,
                    up.profile_path AS author_photo
            FROM {$this->table->getTable('posts')} p
            LEFT JOIN {$this->table->getTable('post_likes')} pl ON p.id = pl.id_post
            LEFT JOIN {$this->table->getTable('post_retweets')} pr ON p.id = pr.id_post
            LEFT JOIN {$this->table->getTable('post_comments')} pc ON p.id = pc.id_post
            JOIN {$this->table->getTable('users')} u ON p.id_user = u.id
            JOIN {$this->table->getTable('profiles')} up ON u.id = up.id_user
            WHERE p.id = :id
            GROUP BY up.profile_path"
        );

        $stmt->bindParam(':id', $id_post, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch();

        if ($result) {
            $post = new Post();
            $post->id = $result['post_id'];
            $post->message = $result['message'];
            $post->count_likes = $result['nb_likes'];
            $post->count_retweets = $result['nb_retweets'];
            $post->count_comments = $result['nb_comments'];
            $post->date_in_minutes = $result['time_difference'];
            $post->author = $result['author'];
            $post->author_photo = $result['author_photo'];

            return $post;
        }

        return null;
    }

    public function getPosts(string $hashtag = null, $pseudo = null): array
    {
        $query = "SELECT p.id AS post_id,
                        p.post AS message,
                        CASE
                            WHEN TIMESTAMPDIFF(SECOND, p.date, NOW()) < 60 THEN CONCAT('il y a ', TIMESTAMPDIFF(SECOND, p.date, NOW()), ' seconde', IF(TIMESTAMPDIFF(SECOND, p.date, NOW()) > 1, 's', ''))
                            WHEN TIMESTAMPDIFF(MINUTE, p.date, NOW()) < 60 THEN CONCAT('il y a ', TIMESTAMPDIFF(MINUTE, p.date, NOW()), ' minute', IF(TIMESTAMPDIFF(MINUTE, p.date, NOW()) > 1, 's', ''))
                            WHEN TIMESTAMPDIFF(HOUR, p.date, NOW()) < 24 THEN CONCAT('il y a ', TIMESTAMPDIFF(HOUR, p.date, NOW()), ' heure', IF(TIMESTAMPDIFF(HOUR, p.date, NOW()) > 1, 's', ''), ' et ', TIMESTAMPDIFF(MINUTE, p.date, NOW()) % 60, ' minute', IF(TIMESTAMPDIFF(MINUTE, p.date, NOW()) % 60 > 1, 's', ''))
                            WHEN TIMESTAMPDIFF(DAY, p.date, NOW()) < 7 THEN CONCAT('il y a ', TIMESTAMPDIFF(DAY, p.date, NOW()), ' jour', IF(TIMESTAMPDIFF(DAY, p.date, NOW()) > 1, 's', ''))
                            WHEN TIMESTAMPDIFF(WEEK, p.date, NOW()) < 4 THEN CONCAT('il y a ', TIMESTAMPDIFF(WEEK, p.date, NOW()), ' semaine', IF(TIMESTAMPDIFF(WEEK, p.date, NOW()) > 1, 's', ''))
                            ELSE DATE_FORMAT(p.date, '%d/%m/%Y')
                        END AS time_difference,
                        COUNT(pl.id_post) AS nb_likes,
                        COUNT(pr.id_post) AS nb_retweets,
                        COUNT(pc.id) AS nb_comments,
                        u.pseudo AS author,
                        up.profile_path AS author_photo
                FROM {$this->table->getTable('posts')} p
                LEFT JOIN {$this->table->getTable('post_likes')} pl ON p.id = pl.id_post
                LEFT JOIN {$this->table->getTable('post_retweets')} pr ON p.id = pr.id_post
                LEFT JOIN {$this->table->getTable('post_comments')} pc ON p.id = pc.id_post
                JOIN {$this->table->getTable('users')} u ON p.id_user = u.id
                JOIN {$this->table->getTable('profiles')} up ON u.id = up.id_user";

        if ($hashtag !== null) {
            $query .= " LEFT JOIN {$this->table->getTable('hashtags')} h ON p.id = h.id_post
                        WHERE h.name = :hashtag";
        }

        if ($pseudo !== null) {
            $query .= " WHERE u.pseudo = :pseudo";
        }

        $query .= " GROUP BY p.id, up.profile_path
        ORDER BY p.date DESC";

        $stmt = $this->connection->getConnection()->prepare($query);

        if ($hashtag !== null) {
            $stmt->bindParam(':hashtag', $hashtag, PDO::PARAM_STR);
        }

        if ($pseudo !== null) {
            $stmt->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
        }

        $stmt->execute();

        $posts = [];

        while ($result = $stmt->fetch()) {
            $post = new Post();
            $post->id = $result['post_id'];
            $post->message = $result['message'];
            $post->count_likes = $result['nb_likes'];
            $post->count_retweets = $result['nb_retweets'];
            $post->count_comments = $result['nb_comments'];
            $post->date_in_minutes = $result['time_difference'];
            $post->author = $result['author'];
            $post->author_photo = $result['author_photo'];

            $posts[] = $post;
        }

        return $posts;
    }

    public function getPostsLinkedToUser(int $id_user): array
    {
        $stmt = $this->connection->getConnection()->prepare(
            "SELECT p.id AS post_id,
                    p.post AS message,
                    CASE
                        WHEN TIMESTAMPDIFF(SECOND, p.date, NOW()) < 60 THEN CONCAT('il y a ', TIMESTAMPDIFF(SECOND, p.date, NOW()), ' seconde', IF(TIMESTAMPDIFF(SECOND, p.date, NOW()) > 1, 's', ''))
                        WHEN TIMESTAMPDIFF(MINUTE, p.date, NOW()) < 60 THEN CONCAT('il y a ', TIMESTAMPDIFF(MINUTE, p.date, NOW()), ' minute', IF(TIMESTAMPDIFF(MINUTE, p.date, NOW()) > 1, 's', ''))
                        WHEN TIMESTAMPDIFF(HOUR, p.date, NOW()) < 24 THEN CONCAT('il y a ', TIMESTAMPDIFF(HOUR, p.date, NOW()), ' heure', IF(TIMESTAMPDIFF(HOUR, p.date, NOW()) > 1, 's', ''), ' et ', TIMESTAMPDIFF(MINUTE, p.date, NOW()) % 60, ' minute', IF(TIMESTAMPDIFF(MINUTE, p.date, NOW()) % 60 > 1, 's', ''))
                        WHEN TIMESTAMPDIFF(DAY, p.date, NOW()) < 7 THEN CONCAT('il y a ', TIMESTAMPDIFF(DAY, p.date, NOW()), ' jour', IF(TIMESTAMPDIFF(DAY, p.date, NOW()) > 1, 's', ''))
                        WHEN TIMESTAMPDIFF(WEEK, p.date, NOW()) < 4 THEN CONCAT('il y a ', TIMESTAMPDIFF(WEEK, p.date, NOW()), ' semaine', IF(TIMESTAMPDIFF(WEEK, p.date, NOW()) > 1, 's', ''))
                        ELSE DATE_FORMAT(p.date, '%d/%m/%Y')
                    END AS time_difference,
                    COUNT(pl.id_post) AS nb_likes,
                    COUNT(pr.id_post) AS nb_retweets,
                    COUNT(pc.id) AS nb_comments,
                    u.pseudo AS author,
                    up.profile_path AS author_photo
            FROM {$this->table->getTable('posts')} p
            LEFT JOIN {$this->table->getTable('post_likes')} pl ON p.id = pl.id_post
            LEFT JOIN {$this->table->getTable('post_retweets')} pr ON p.id = pr.id_post
            LEFT JOIN {$this->table->getTable('post_comments')} pc ON p.id = pc.id_post
            JOIN {$this->table->getTable('users')} u ON p.id_user = u.id
            JOIN {$this->table->getTable('profiles')} up ON u.id = up.id_user
            WHERE p.id_user = :id_user
                OR pl.id_user = :id_user
                OR pr.id_user = :id_user
            GROUP BY p.id, up.profile_path
            ORDER BY p.date DESC"
        );

        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->execute();

        $posts = [];

        while ($result = $stmt->fetch()) {
            $post = new Post();
            $post->id = $result['post_id'];
            $post->message = $result['message'];
            $post->count_likes = $result['nb_likes'];
            $post->count_retweets = $result['nb_retweets'];
            $post->count_comments = $result['nb_comments'];
            $post->date_in_minutes = $result['time_difference'];
            $post->author = $result['author'];
            $post->author_photo = $result['author_photo'];

            $posts[] = $post;
        }

        return $posts;
    }

    public function countPosts(string $hashtag = null, string $pseudo = null): array
    {
        $query = "SELECT COUNT(p.id) AS nb_posts
                  FROM {$this->table->getTable('posts')} p";

        if ($pseudo !== null) {
            $query .= " JOIN {$this->table->getTable('users')} u ON p.id_user = u.id";
        }

        if ($hashtag !== null) {
            $query .= " LEFT JOIN {$this->table->getTable('hashtags')} h ON p.id = h.id_post";
        }

        $whereAdded = false;

        if ($hashtag !== null) {
            $query .= " WHERE h.name = :hashtag";
            $whereAdded = true;
        }

        if ($pseudo !== null) {
            if ($whereAdded) {
                $query .= " AND u.pseudo = :pseudo";
            } else {
                $query .= " WHERE u.pseudo = :pseudo";
            }
        }

        $stmt = $this->connection->getConnection()->prepare($query);

        if ($hashtag !== null) {
            $stmt->bindParam(':hashtag', $hashtag, \PDO::PARAM_STR);
        }

        if ($pseudo !== null) {
            $stmt->bindParam(':pseudo', $pseudo, \PDO::PARAM_STR);
        }

        $stmt->execute();

        return $stmt->fetch();
    }

    public function getHashtag(string $hashtag): bool
    {
        $stmt = $this->connection->getConnection()->prepare(
            "SELECT name
            FROM {$this->table->getTable('hashtags')}
            WHERE name = :name"
        );

        $stmt->bindParam(':name', $hashtag, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function getHashtags(?int $limit = null): array
    {
        $sql = "SELECT name, MAX(id) AS max_id
                FROM {$this->table->getTable('hashtags')}
                GROUP BY name
                ORDER BY max_id DESC";

        if ($limit !== null) {
            $sql .= " LIMIT :limit";
        }

        $stmt = $this->connection->getConnection()->prepare($sql);

        if ($limit !== null) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        }

        $stmt->execute();

        $hashtags = [];

        while ($result = $stmt->fetch()) {
            $hashtags[] = $result['name'];
        }

        return $hashtags;
    }
}
