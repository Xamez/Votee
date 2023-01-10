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

    private function __construct() {
        $dbname = Conf::getDatabase();
        $login = Conf::getLogin();
        $password = Conf::getPassword();

        //  Connexion base de donnée
        $this->pdo = new PDO("oci:dbname=".$dbname.";charset=UTF8", $login, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    private static function getInstance(): DatabaseConnection {
        if (is_null(static::$instance))
            static::$instance = new DatabaseConnection();
        return static::$instance;
    }
}