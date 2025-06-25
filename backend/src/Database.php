<?php
namespace App;

use PDO;

class Database {
    private $pdo;

    public function __construct() {
        $host = 'localhost';
        $dbname = 'crops_monitoring';
        $username = 'root'; // Update with your MySQL username
        $password = ''; // Update with your MySQL password

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->pdo;
    }
}