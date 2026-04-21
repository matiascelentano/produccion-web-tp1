<?php
class DB {
    private static $host = "localhost";
    private static $db_name = "concesionaria";
    private static $username = "root";
    private static $password = "";
    private static $con = null;

    public static function getConnection() {
        if(self::$con === null) {
            try {
                self::$con = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$db_name, self::$username, self::$password);
                self::$con->exec("set names utf8");
            } catch(PDOException $exception) {
                die("Error: " . $exception->getMessage());
            }
        }
        return self::$con;
    }
}
