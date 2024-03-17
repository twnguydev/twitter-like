<?php

namespace App\Feature\Chat;

use App\Config\Database\ConfigDatabase;
use App\Config\Database\DatabaseConnection;
use App\User\User;
use App\User\UserRepository;
use PDOException;
use PDO;

class Chat
{
    public int $id;
    public int $id_user;
    public int $id_target;
    public string $message;
    public string $date;
    public bool $isSender;
    public bool $isReceiver;
}

class ChatRepository
{
    public DatabaseConnection $connection;
    public ConfigDatabase $table;

    public function __construct()
    {
        $this->connection = new DatabaseConnection();
        $this->table = new ConfigDatabase();
    }

    public function getPersonsWhoChattedWith(int $id_user): ?array
    {
        $stmt = $this->connection->getConnection()->prepare(
            "SELECT u.id,
                    u.pseudo,
                    u.email,
                    p.profile_path
            FROM {$this->table->getTable('users')} u
            JOIN {$this->table->getTable('profiles')} p ON u.id = p.id_user
            JOIN (
                SELECT id_receiver AS user_id FROM {$this->table->getTable('user_message')} WHERE id_sender = :id
                UNION
                SELECT id_sender AS user_id FROM {$this->table->getTable('user_message')} WHERE id_receiver = :id
            ) AS um ON u.id = um.user_id
            WHERE u.id != :id
            GROUP BY u.id, p.profile_path"
        );

        $stmt->bindParam(':id', $id_user, PDO::PARAM_INT);
        $stmt->execute();

        $users = [];

        while ($result = $stmt->fetch()) {
            $user = new User();
            $user->id = $result['id'];
            $user->pseudo = $result['pseudo'];
            $user->email = $result['email'];
            $user->profile_path = $result['profile_path'];

            $users[] = $user;
        }

        return $users;
    }

    public function getChats(): array
    {
        $stmt = $this->connection->getConnection()->prepare(
            "SELECT id,
                    id_sender,
                    id_receiver,
                    message,
                    DATE_FORMAT(date, '%d/%m/%Y %H:%i:%s') AS date
            FROM {$this->table->getTable('user_message')}
            ORDER BY date ASC"
        );

        $stmt->execute();

        $chats = [];

        while ($result = $stmt->fetch()) {
            $chat = new Chat();
            $chat->id = $result['id'];
            $chat->id_user = $result['id_sender'];
            $chat->id_target = $result['id_receiver'];
            $chat->message = $result['message'];
            $chat->date = $result['date'];

            $chats[] = $chat;
        }

        return $chats;
    }

    public function setChatRegistration(int $id_user, int $id_target, string $message): bool
    {
        $stmt = $this->connection->getConnection()->prepare(
            "INSERT INTO {$this->table->getTable('user_message')} (id_sender, id_receiver, message, date)
            VALUES (:id_user, :id_target, :message, NOW())"
        );

        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->bindParam(':id_target', $id_target, PDO::PARAM_INT);
        $stmt->bindParam(':message', $message, PDO::PARAM_STR);

        return $stmt->execute();
    }
}
