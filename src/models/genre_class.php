<?php

namespace App\User\Genre;

use App\Config\Database\DatabaseConnection;
use App\Config\Database\ConfigDatabase;
use PDO;
use PDOException;

class Genre
{
    public string $id;
    public string $name;
    public string $total_user;
}

class GenreRepository
{
    public DatabaseConnection $connection;
    public ConfigDatabase $table;

    public function __construct()
    {
        $this->connection = new DatabaseConnection();
        $this->table = new ConfigDatabase();
    }

    public function getGenre(int $genre_id = null, string $genre_name = null): ?Genre
    {
        $statement = $this->connection->getConnection()->prepare(
            "SELECT g.id,
                    g.name,
                    COUNT(u.id) AS total_user
            FROM {$this->table->getTable('genres')} g
            LEFT JOIN {$this->table->getTable('users')} u ON g.id = u.id_genre
            WHERE g.id = :genre_id
                OR g.name = :genre_name
            GROUP BY g.id"
        );
    
        $statement->bindParam(':genre_id', $genre_id, PDO::PARAM_INT);
        $statement->bindParam(':genre_name', $genre_name, PDO::PARAM_STR);
    
        $statement->execute();
        
        $result = $statement->fetch();
        
        if (!$result) {
            return null;
        }

        $genre = new Genre();
        $genre->id            = $result['id'];
        $genre->name          = $result['name'];
        $genre->total_user    = $result['total_user'];

        return $genre;
    }

    public function getGenres(): array
    {
        $statement = $this->connection->getConnection()->query(
            "SELECT g.id,
                    g.name,
                    COUNT(u.id) AS total_user
            FROM {$this->table->getTable('genres')} g
            LEFT JOIN {$this->table->getTable('users')} u ON g.id = u.id_genre
            GROUP BY g.id"
        );                

        $genres = [];

        while ($result = $statement->fetch()) {
            $genre = new Genre();
            $genre->id            = $result['id'];
            $genre->name          = $result['name'];
            $genre->total_user  = $result['total_user'];

            $genres[] = $genre;
        }

        return $genres;
    }
}