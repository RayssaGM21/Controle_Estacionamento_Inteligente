<?php
require __DIR__ . '/../vendor/autoload.php';

try {
	$pdo = new PDO('sqlite:' . __DIR__ . '/parking.sqlite');
	$tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll();
	print_r($tables);
} catch (Throwable $e) {
	echo "Erro: " . $e->getMessage() . PHP_EOL;
}