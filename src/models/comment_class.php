<?php

namespace App\Feature\Post\Comment;

use App\Config\Database\ConfigDatabase;
use App\Config\Database\DatabaseConnection;
use PDO;
use PDOException;

class Comment
{
    public int $id;
    public int $id_user;
    public int $id_post;
    public string $author;
    public string $author_photo;
    public string $comment;
    public string $date;
    public string $date_in_minutes;
    public int $count_likes;
    public int $count_replies;
}

class CommentRepository
{
    public DatabaseConnection $connection;
    public ConfigDatabase $table;

    public function __construct()
    {
        $this->connection = new DatabaseConnection();
        $this->table = new ConfigDatabase();
    }

    public function getComments(int $id_post): array
    {
        $commentsStmt = $this->connection->getConnection()->prepare(
            "SELECT pc.id AS comment_id,
                    u.pseudo AS comment_author,
                    up.profile_path AS comment_author_photo,
                    pc.content AS comment,
                    CASE
                        WHEN TIMESTAMPDIFF(SECOND, pc.date, NOW()) < 60 THEN CONCAT('il y a ', TIMESTAMPDIFF(SECOND, pc.date, NOW()), ' seconde', IF(TIMESTAMPDIFF(SECOND, pc.date, NOW()) > 1, 's', ''))
                        WHEN TIMESTAMPDIFF(MINUTE, pc.date, NOW()) < 60 THEN CONCAT('il y a ', TIMESTAMPDIFF(MINUTE, pc.date, NOW()), ' minute', IF(TIMESTAMPDIFF(MINUTE, pc.date, NOW()) > 1, 's', ''))
                        WHEN TIMESTAMPDIFF(HOUR, pc.date, NOW()) < 24 THEN CONCAT('il y a ', TIMESTAMPDIFF(HOUR, pc.date, NOW()), ' heure', IF(TIMESTAMPDIFF(HOUR, pc.date, NOW()) > 1, 's', ''), ' et ', TIMESTAMPDIFF(MINUTE, pc.date, NOW()) % 60, ' minute', IF(TIMESTAMPDIFF(MINUTE, pc.date, NOW()) % 60 > 1, 's', ''))
                        WHEN TIMESTAMPDIFF(DAY, pc.date, NOW()) < 7 THEN CONCAT('il y a ', TIMESTAMPDIFF(DAY, pc.date, NOW()), ' jour', IF(TIMESTAMPDIFF(DAY, pc.date, NOW()) > 1, 's', ''))
                        WHEN TIMESTAMPDIFF(WEEK, pc.date, NOW()) < 4 THEN CONCAT('il y a ', TIMESTAMPDIFF(WEEK, pc.date, NOW()), ' semaine', IF(TIMESTAMPDIFF(WEEK, pc.date, NOW()) > 1, 's', ''))
                        ELSE DATE_FORMAT(pc.date, '%d/%m/%Y')
                    END AS time_difference
            FROM {$this->table->getTable('post_comments')} pc
            LEFT JOIN {$this->table->getTable('posts')} p ON p.id = pc.id_post
            LEFT JOIN {$this->table->getTable('users')} u ON pc.id_user = u.id
            LEFT JOIN {$this->table->getTable('profiles')} up ON u.id = up.id_user
            WHERE p.id = :id_post"
        );

        $commentsStmt->bindParam(':id_post', $id_post, PDO::PARAM_INT);
        $commentsStmt->execute();

        $comments = [];

        while ($result = $commentsStmt->fetch()) {
            $comment = new Comment();
            $comment->id = $result['comment_id'];
            $comment->author = $result['comment_author'];
            $comment->author_photo = $result['comment_author_photo'];
            $comment->comment = $result['comment'];
            $comment->date = $result['time_difference'];

            $comments[] = $comment;
        }

        return $comments;
    }

    public function setCommentRegistration(int $id_user, int $id_post, string $message, ?array $hashtags): bool
    {
        $commentStmt = $this->connection->getConnection()->prepare(
            "INSERT INTO {$this->table->getTable('post_comments')} (id_user, id_post, content, date)
            VALUES (:id_user, :id_post, :content, NOW())"
        );

        $commentStmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $commentStmt->bindParam(':id_post', $id_post, PDO::PARAM_INT);
        $commentStmt->bindParam(':content', $message, PDO::PARAM_STR);

        $result = $commentStmt->execute();

        if ($result) {
            $lastCommentId = $this->connection->getConnection()->lastInsertId();

            foreach ($hashtags as $hashtag) {
                $hashtagStmt = $this->connection->getConnection()->prepare(
                    "INSERT INTO {$this->table->getTable('hashtags')} (name, id_comment)
                    VALUES (:name, :id_comment)"
                );

                $hashtagStmt->bindParam(':name', $hashtag, PDO::PARAM_STR);
                $hashtagStmt->bindParam(':id_comment', $lastCommentId, PDO::PARAM_INT);

                $result = $hashtagStmt->execute();

                if (!$result) {
                    return false;
                }
            }
        }

        return true;
    }
}
