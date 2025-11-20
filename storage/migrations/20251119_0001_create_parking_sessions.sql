-- Migration: 20251119_0001_create_parking_sessions.sql
-- Cria a tabela parking_sessions utilizada pela aplicação

CREATE TABLE IF NOT EXISTS parking_sessions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    plate TEXT NOT NULL,
    vehicle_type TEXT NOT NULL,
    parked_hours INTEGER NOT NULL,
    final_tariff REAL NOT NULL,
    entry_time TEXT NOT NULL,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
);
