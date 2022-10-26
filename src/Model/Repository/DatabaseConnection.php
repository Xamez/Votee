<?php

namespace App\Votee\Model\Repository;

use App\Votee\Config\Conf;
use PDO;

class DatabaseConnection {
    private static ?DatabaseConnection $instance = null;

    private PDO $pdo;

    public static function getPdo(): PDO {
        return static::getInstance()->pdo;
    }

    public function __construct() {
        $dbname = Conf::getDatabase();
        $login = Conf::getLogin();
        $password = Conf::getPassword();

        $this->pdo = new PDO("oci:dbname=".$dbname, $login, $password, 'AL32UTF8');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    private static function getInstance(): DatabaseConnection {
        if (is_null(static::$instance))
            static::$instance = new DatabaseConnection();
        return static::$instance;
    }
}