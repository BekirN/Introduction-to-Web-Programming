<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ (E_NOTICE | E_DEPRECATED));

class Config {
    public static function DB_HOST() {
        return 'localhost';
    }

    public static function DB_NAME() {
        return 'car_marketplace1';
    }

    public static function DB_PORT() {
        return '3306';
    }

    public static function DB_USER() {
        return 'root';
    }

    public static function DB_PASSWORD() {
        return 'bekirnokic2004';
    }
    public static function JWT_SECRET() {
        return 'strongsecurerandomstring';
    }
}