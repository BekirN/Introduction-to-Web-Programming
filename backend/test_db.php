<?php
require_once __DIR__ . '/config.php';

echo "DB_HOST: " . Config::DB_HOST() . "<br>";
echo "DB_NAME: " . Config::DB_NAME() . "<br>";
echo "DB_PORT: " . Config::DB_PORT() . "<br>";
echo "DB_USER: " . Config::DB_USER() . "<br>";
echo "DB_PASSWORD: " . Config::DB_PASSWORD() . "<br>";

try {
    $dsn = "mysql:host=" . Config::DB_HOST() . ";dbname=" . Config::DB_NAME() . ";port=" . Config::DB_PORT();
    $pdo = new PDO($dsn, Config::DB_USER(), Config::DB_PASSWORD());
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Database connection successful!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>