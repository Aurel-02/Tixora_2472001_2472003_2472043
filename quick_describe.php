<?php
try {
    $dbh = new PDO('mysql:host=127.0.0.1;dbname=ticketevent', 'root', '');
    $tables = ['detail_transaksi', 'tiket'];

    foreach ($tables as $table) {
        echo "--- $table ---\n";
        $columns = $dbh->query("DESCRIBE $table")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($columns as $column) {
            echo "{$column['Field']}\n";
        }
    }
} catch (\PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
