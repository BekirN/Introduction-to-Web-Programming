<?php
class Database {
    public static function connect() {
        $dsn = "mysql:host=localhost;dbname=your_database;charset=utf8";
        $username = "root";
        $password = "";
        return new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }
}