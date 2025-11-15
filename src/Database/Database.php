<?php

declare(strict_types=1);

namespace App\Database;

use PDO;
use PDOException;

/**
 * Class Database
 * Lida com conexão SQLite usando PDO.
 */
class Database
{
    private static ?PDO $pdo = null;

    /**
     * Retorna a instância PDO.
     *
     * @return PDO
     */
    public static function getConnection(): PDO
    {
        if (self::$pdo === null) {
            try {
                self::$pdo = new PDO('sqlite:' . __DIR__ . '/database.sqlite');
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die('Conexão com o database falhou: ' . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}
