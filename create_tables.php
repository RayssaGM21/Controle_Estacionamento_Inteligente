<?php
declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use App\Database\Database;

$db = Database::getConnection();

/**
 * Tabela de tarifas
 * carro: 5/h, moto: 3/h, caminhao: 10/h
 */
$db->exec("
    CREATE TABLE IF NOT EXISTS tariffs (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        vehicle_type TEXT NOT NULL UNIQUE,
        price_per_hour REAL NOT NULL
    );
");

/**
 * Tabela de veículos
 */
$db->exec("
    CREATE TABLE IF NOT EXISTS vehicles (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        plate TEXT NOT NULL UNIQUE,
        vehicle_type TEXT NOT NULL,
        created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
    );
");


/**
 * Tabela de entradas de estacionamento
 */
$db->exec("
    CREATE TABLE IF NOT EXISTS parking_entries (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        vehicle_id INTEGER NOT NULL,
        entry_time TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (vehicle_id) REFERENCES vehicles(id)
    );
");

/**
 * Tabela de saídas e cobrança
 */
$db->exec("
    CREATE TABLE IF NOT EXISTS parking_exits (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        entry_id INTEGER NOT NULL,
        exit_time TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
        total_amount REAL NOT NULL,
        hours INTEGER NOT NULL,
        FOREIGN KEY (entry_id) REFERENCES parking_entries(id)
    );
");

echo "Tabelas criadas com sucesso.\n";

/**
 * Inserir tarifas padrão, se não existirem
 */
$tariffs = [
    ['carro', 5],
    ['moto', 3],
    ['caminhao', 10],
];

$stmt = $db->prepare("INSERT OR IGNORE INTO tariffs (vehicle_type, price_per_hour) VALUES (:type, :price)");

foreach ($tariffs as [$type, $price]) {
    $stmt->execute([
        ':type' => $type,
        ':price' => $price
    ]);
}

echo "Tarifas padrão inseridas.\n";
