<?php

class DataBase
{
    private PDO $connection;

    public function __construct(
        private string $host = 'mysql',
        private int    $port = 3306,
        private string $user = 'root',
        private string $password = '',
        private string $database = 'test'
    )
    {
        $this->createDB();
        $this->connection = new PDO($this->getDsn(true), $this->user, $this->password);
    }

    private function getDsn(bool $withDatabase = true): string
    {
        return "mysql:host=$this->host;port:$this->port;charset=UTF8" . ($withDatabase ? ';dbname=' . $this->database : '');

    }

    private function createDB(): void
    {
        $db = new PDO($this->getDsn(false), $this->user, $this->password);
        $db->query(/** @lang=MariaDB */ <<<SQL
            CREATE DATABASE IF NOT EXISTS `$this->database`
            CHARACTER SET 'utf8mb4' 
            COLLATE 'utf8mb4_general_ci'
        SQL
        );
    }

    function createTable(string $table, string $index): void
    {
        $this->connection->query(/** @lang=MariaDB */ <<<SQL
         CREATE TABLE IF NOT EXISTS `$table`
            (
                `id` BIGINT UNSIGNED AUTO_INCREMENT,
                `gender` ENUM('male', 'female'),
                `age` TINYINT UNSIGNED,
                PRIMARY KEY (`id`),
                $index
            )
            ENGINE = InnoDB
            CHARACTER SET 'utf8mb4' 
            COLLATE 'utf8mb4_general_ci'
        SQL
        );
    }

    function fillTable(string $table, int $size = 1_000_000, int $chunk = 5000): void
    {
        $count = $this->connection->query(/** @lang=MariaDB */ <<<SQL
            SELECT count(`id`) as `total` FROM `$table`
        SQL
        )->fetch(PDO::FETCH_ASSOC)['total'];

        if ($count === $size) {
            return;
        } elseif ($count > $size) {
            $this->connection->query(/** @lang=MariaDB */ <<<SQL
               TRUNCATE TABLE `$table`
               SQL
            );
        } else {
            $size -= $count;
        }

        $bulk = [];
        foreach (range(1, $size) as $_) {
            $bulk[] = sprintf("('%s', %s)", rand(0, 1) === 1 ? 'male' : 'female', rand(18, 75));
            if (count($bulk) >= $chunk) {
                $insertSql = implode(",\n", $bulk);
                $bulk = [];
                $this->connection->query(/** @lang=MariaDB */ <<<SQL
                   INSERT INTO `$table` (`gender`, `age`)
                   VALUES 
                       $insertSql
                SQL
                );
            }
        }
        if (count($bulk) > 0) {
            $insertSql = implode(",\n", $bulk);
            $bulk = [];
            $this->connection->query(/** @lang=MariaDB */ <<<SQL
               INSERT INTO `$table` (`gender`, `age`)
               VALUES 
                   $insertSql
            SQL
            );
        }
    }

    public function copyTable(string $from, string $to)
    {
        $this->connection->query(/** @lang=MariaDB */ "TRUNCATE TABLE `$to`");
        $this->connection->query(/** @lang=MariaDB */ "INSERT INTO `$to` SELECT * FROM `$from`");
    }

    public function count(string $table, string $query): int
    {
        return $this->connection->query(/** @lang=MariaDB */ <<<SQL
            SELECT SQL_NO_CACHE count(*) as `total` FROM `$table`
            WHERE $query
        SQL
        )->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function explain(string $table, string $query): string
    {
        return $this->connection->query(/** @lang=MariaDB */ <<<SQL
            ANALYZE FORMAT=JSON SELECT SQL_NO_CACHE count(*) as `total` FROM `$table`
            WHERE $query
        SQL
        )->fetch(PDO::FETCH_ASSOC)['ANALYZE'];
    }
}


