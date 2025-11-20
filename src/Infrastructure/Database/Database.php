<?php

declare(strict_types=1);

namespace App\Infrastructure\Database;

use PDO;

/**
 * Banco de Dados
 *
 * Singleton para gerenciamento de conexão com banco SQLite.
 */
final class Database
{
    private static ?self $instance = null;

    private ?PDO $connection = null;

    private string $dbPath;

    private function __construct(string $dbPath)
    {
        $this->dbPath = $dbPath;
        $this->connect();
    }

    public static function getInstance(?string $dbPath = null): self
    {
        if (self::$instance === null) {
            if ($dbPath === null) {
                $dbPath = dirname(__DIR__, 3) . '/storage/parking.sqlite';
            }

            $dir = dirname($dbPath);
            if (!is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }

            self::$instance = new self($dbPath);
        }

        return self::$instance;
    }

    private function connect(): void
    {
        try {
            $this->connection = new PDO('sqlite:' . $this->dbPath);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \RuntimeException('Falha ao conectar ao banco SQLite: ' . $e->getMessage(), 0, $e);
        }
    }

    private function createTablesIfNotExist(): void
    {
        $sql = <<<'SQL'
CREATE TABLE IF NOT EXISTS parking_sessions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    plate TEXT NOT NULL,
    vehicle_type TEXT NOT NULL,
    parked_hours INTEGER NOT NULL,
    final_tariff REAL NOT NULL,
    entry_time TEXT NOT NULL,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
);
SQL;

        $this->connection->exec($sql);
    }

    public function getConnection(): PDO
    {
        if ($this->connection === null) {
            $this->connect();
        }

        return $this->connection;
    }

    public function closeConnection(): void
    {
        $this->connection = null;
    }

    private function __clone()
    {
    }

    public function __wakeup(): void
    {
    }
}
