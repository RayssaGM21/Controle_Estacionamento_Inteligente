<?php
declare(strict_types=1);

/**
 * Runner simples de migrations SQLite
 *
 * Uso: php storage/migrate.php
 * Procura arquivos SQL em `storage/migrations` ordenados por nome
 * e aplica as migrations ainda não aplicadas. As migrations aplicadas
 * são registradas na tabela `migrations` do mesmo banco SQLite.
 */

require __DIR__ . '/../vendor/autoload.php';

use App\Infrastructure\Database\Database;

try {
    $pdo = Database::getInstance()->getConnection();
} catch (Throwable $e) {
    echo "Erro ao obter conexão com o banco: " . $e->getMessage() . PHP_EOL;
    exit(1);
}

// Ensure migrations table exists
$pdo->exec(<<<'SQL'
CREATE TABLE IF NOT EXISTS migrations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL UNIQUE,
    applied_at TEXT NOT NULL
);
SQL
);

$migrationsDir = __DIR__ . '/migrations';
if (!is_dir($migrationsDir)) {
    echo "Diretório de migrations não encontrado: {$migrationsDir}" . PHP_EOL;
    exit(1);
}

$files = array_values(array_filter(scandir($migrationsDir), function ($f) use ($migrationsDir) {
    return is_file($migrationsDir . DIRECTORY_SEPARATOR . $f) && preg_match('/\.sql$/i', $f);
}));

sort($files, SORT_STRING);

foreach ($files as $file) {
    $name = $file;

    // Check if applied
    $stmt = $pdo->prepare('SELECT 1 FROM migrations WHERE name = :name');
    $stmt->execute([':name' => $name]);
    if ($stmt->fetchColumn() !== false) {
        echo "[SKIP] {$name} (já aplicada)" . PHP_EOL;
        continue;
    }

    $path = $migrationsDir . DIRECTORY_SEPARATOR . $file;
    $sql = file_get_contents($path);
    if ($sql === false) {
        echo "[ERROR] Falha ao ler migration: {$name}" . PHP_EOL;
        continue;
    }

    echo "[APPLY] {$name}... ";

    try {
        $pdo->beginTransaction();
        // Execute SQL. For sqlite PDO::exec can execute multiple statements.
        $pdo->exec($sql);
        $ins = $pdo->prepare('INSERT INTO migrations (name, applied_at) VALUES (:name, :applied_at)');
        $ins->execute([':name' => $name, ':applied_at' => (new DateTime())->format('Y-m-d H:i:s')]);
        $pdo->commit();
        echo "OK" . PHP_EOL;
    } catch (Throwable $e) {
        $pdo->rollBack();
        echo "FAIL\n";
        echo "       " . $e->getMessage() . PHP_EOL;
        exit(1);
    }
}

echo "Migrations finalizadas." . PHP_EOL;
