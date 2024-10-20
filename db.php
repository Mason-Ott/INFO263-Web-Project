<?php
try {
    // Path to the SQLite database file relative to the 'php_handlers' folder
    $dbPath = __DIR__ . '../RentalSimV8Logging.2024-08-09.db';
    $pdo = new PDO("sqlite:$dbPath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit();
}
?>
