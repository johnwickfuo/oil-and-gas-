<?php
// deployment/sqlite_to_mysql.php
// One-off helper used during Phase 8 to emit a MySQL-compatible SQL dump of
// the seeded SQLite database. Run with: php deployment/sqlite_to_mysql.php

$sqlitePath = __DIR__ . '/../database/database.sqlite';
$outputPath = __DIR__ . '/bluedine.sql';

if (! file_exists($sqlitePath)) {
    fwrite(STDERR, "SQLite database not found at {$sqlitePath}\n");
    exit(1);
}

$pdo = new PDO('sqlite:' . $sqlitePath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function mapType(string $sqliteType, bool $autoIncrement, bool $unsigned): string
{
    $t = strtolower(trim($sqliteType));

    if ($autoIncrement) {
        return 'BIGINT UNSIGNED AUTO_INCREMENT';
    }

    if ($t === 'integer' || $t === 'int') {
        return $unsigned ? 'BIGINT UNSIGNED' : 'BIGINT';
    }
    if ($t === 'tinyint(1)' || $t === 'tinyint') {
        return 'TINYINT(1)';
    }
    if (preg_match('/^varchar\((\d+)\)$/i', $t, $m)) {
        return "VARCHAR({$m[1]})";
    }
    if ($t === 'varchar' || $t === 'char') {
        return 'VARCHAR(255)';
    }
    if ($t === 'text') {
        return 'TEXT';
    }
    if ($t === 'longtext') {
        return 'LONGTEXT';
    }
    if ($t === 'mediumtext') {
        return 'MEDIUMTEXT';
    }
    if ($t === 'datetime') {
        return 'DATETIME';
    }
    if ($t === 'date') {
        return 'DATE';
    }
    if ($t === 'time') {
        return 'TIME';
    }
    if ($t === 'timestamp') {
        return 'TIMESTAMP';
    }
    if (preg_match('/^decimal\((\d+),\s*(\d+)\)$/i', $t, $m)) {
        return "DECIMAL({$m[1]},{$m[2]})";
    }
    if ($t === 'float' || $t === 'real' || $t === 'double') {
        return 'DOUBLE';
    }
    if ($t === 'blob') {
        return 'LONGBLOB';
    }
    if ($t === 'json') {
        return 'JSON';
    }
    if ($t === 'numeric') {
        return 'DECIMAL(20,6)';
    }

    return strtoupper($t);
}

function escapeIdent(string $name): string
{
    return '`' . str_replace('`', '``', $name) . '`';
}

function escapeValue($v, PDO $pdo): string
{
    if ($v === null) {
        return 'NULL';
    }
    if (is_int($v) || is_float($v)) {
        return (string) $v;
    }
    if (is_bool($v)) {
        return $v ? '1' : '0';
    }
    return $pdo->quote((string) $v);
}

$tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name")->fetchAll(PDO::FETCH_COLUMN);

$skip = []; // emit every table
$out = [];
$out[] = "-- Blue Dine Cuisines — seeded database";
$out[] = "-- Generated: " . date('c');
$out[] = "-- MySQL 8.0 compatible. Import into a pre-created database.";
$out[] = "";
$out[] = "SET NAMES utf8mb4;";
$out[] = "SET FOREIGN_KEY_CHECKS=0;";
$out[] = "SET @OLD_SQL_MODE=@@SQL_MODE;";
$out[] = "SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';";
$out[] = "";

foreach ($tables as $table) {
    if (in_array($table, $skip, true)) continue;

    $cols = $pdo->query("PRAGMA table_info(" . escapeIdent($table) . ")")->fetchAll(PDO::FETCH_ASSOC);
    $fks = $pdo->query("PRAGMA foreign_key_list(" . escapeIdent($table) . ")")->fetchAll(PDO::FETCH_ASSOC);
    $indexList = $pdo->query("PRAGMA index_list(" . escapeIdent($table) . ")")->fetchAll(PDO::FETCH_ASSOC);

    $createSql = $pdo->query("SELECT sql FROM sqlite_master WHERE type='table' AND name=" . $pdo->quote($table))->fetchColumn();
    $autoIncrement = stripos($createSql, 'AUTOINCREMENT') !== false;

    $pkCols = [];
    foreach ($cols as $c) {
        if ($c['pk'] > 0) $pkCols[$c['pk']] = $c['name'];
    }
    ksort($pkCols);
    $pkCols = array_values($pkCols);

    $lines = [];
    foreach ($cols as $c) {
        $name = $c['name'];
        $type = $c['type'] ?: 'TEXT';

        $isAutoPk = $autoIncrement && count($pkCols) === 1 && $pkCols[0] === $name && strtolower($type) === 'integer';
        $unsigned = $isAutoPk;

        $mysqlType = mapType($type, $isAutoPk, $unsigned);
        $line = '    ' . escapeIdent($name) . ' ' . $mysqlType;

        if ($c['notnull']) {
            $line .= ' NOT NULL';
        } else {
            $line .= ' NULL';
        }

        if ($c['dflt_value'] !== null && ! $isAutoPk) {
            $def = $c['dflt_value'];
            if (preg_match('/^CURRENT_TIMESTAMP$/i', $def)) {
                $line .= ' DEFAULT CURRENT_TIMESTAMP';
            } else {
                $line .= ' DEFAULT ' . $def;
            }
        }

        $lines[] = $line;
    }

    if (! empty($pkCols)) {
        $lines[] = '    PRIMARY KEY (' . implode(', ', array_map('escapeIdent', $pkCols)) . ')';
    }

    $addedIndexes = [];
    foreach ($indexList as $idx) {
        if ($idx['origin'] === 'pk') continue;
        if (strpos($idx['name'], 'sqlite_autoindex') === 0) continue;

        $idxCols = $pdo->query("PRAGMA index_info(" . escapeIdent($idx['name']) . ")")->fetchAll(PDO::FETCH_ASSOC);
        $cnames = array_map(fn ($r) => $r['name'], $idxCols);

        $idxName = $idx['name'];
        $key = 'idx::' . implode(',', $cnames);
        if (isset($addedIndexes[$key])) continue;
        $addedIndexes[$key] = true;

        if ($idx['unique']) {
            $lines[] = '    UNIQUE KEY ' . escapeIdent($idxName) . ' (' . implode(', ', array_map('escapeIdent', $cnames)) . ')';
        } else {
            $lines[] = '    KEY ' . escapeIdent($idxName) . ' (' . implode(', ', array_map('escapeIdent', $cnames)) . ')';
        }
    }

    foreach ($fks as $fk) {
        $fkCol = $fk['from'];
        $refTable = $fk['table'];
        $refCol = $fk['to'];
        $onDelete = ! empty($fk['on_delete']) ? " ON DELETE " . strtoupper($fk['on_delete']) : '';
        $onUpdate = ! empty($fk['on_update']) ? " ON UPDATE " . strtoupper($fk['on_update']) : '';
        $fkName = 'fk_' . substr(md5($table . '_' . $fkCol . '_' . $refTable . '_' . $refCol), 0, 16);
        $lines[] = '    CONSTRAINT ' . escapeIdent($fkName) . ' FOREIGN KEY (' . escapeIdent($fkCol) . ') REFERENCES ' . escapeIdent($refTable) . ' (' . escapeIdent($refCol) . ')' . $onDelete . $onUpdate;
    }

    $out[] = '-- -----------------------------';
    $out[] = '-- Table: ' . $table;
    $out[] = '-- -----------------------------';
    $out[] = 'DROP TABLE IF EXISTS ' . escapeIdent($table) . ';';
    $out[] = 'CREATE TABLE ' . escapeIdent($table) . " (";
    $out[] = implode(",\n", $lines);
    $out[] = ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    $out[] = '';

    // Data
    $rows = $pdo->query("SELECT * FROM " . escapeIdent($table))->fetchAll(PDO::FETCH_ASSOC);
    if (empty($rows)) {
        $out[] = '-- no data';
        $out[] = '';
        continue;
    }

    $colNames = array_keys($rows[0]);
    $colList = implode(', ', array_map('escapeIdent', $colNames));

    $chunks = array_chunk($rows, 100);
    foreach ($chunks as $chunk) {
        $valueLines = [];
        foreach ($chunk as $row) {
            $vals = [];
            foreach ($colNames as $cn) {
                $vals[] = escapeValue($row[$cn], $pdo);
            }
            $valueLines[] = '    (' . implode(', ', $vals) . ')';
        }
        $out[] = 'INSERT INTO ' . escapeIdent($table) . ' (' . $colList . ') VALUES';
        $out[] = implode(",\n", $valueLines) . ';';
        $out[] = '';
    }
}

$out[] = 'SET SQL_MODE=@OLD_SQL_MODE;';
$out[] = 'SET FOREIGN_KEY_CHECKS=1;';
$out[] = '';

file_put_contents($outputPath, implode("\n", $out));
echo "Wrote " . strlen(implode("\n", $out)) . " bytes to {$outputPath}\n";
